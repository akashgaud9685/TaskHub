<?php

class SupabaseStatement
{
    private $pdo;
    private $sql;
    private $params = [];
    private $result = null;
    private $rowIndex = 0;
    private $rowCount = 0;
    private $tableName = '';
    private $operation = '';
    private static $requestCache = [];

    public function __construct($pdo, $sql)
    {
        $this->pdo = $pdo;
        $this->sql = trim($sql);
        $upper = strtoupper($this->sql);
        if (strpos($upper, 'SELECT') === 0) $this->operation = 'SELECT';
        elseif (strpos($upper, 'INSERT') === 0) $this->operation = 'INSERT';
        elseif (strpos($upper, 'UPDATE') === 0) $this->operation = 'UPDATE';
        elseif (strpos($upper, 'DELETE') === 0) $this->operation = 'DELETE';
        elseif (strpos($upper, 'SHOW') === 0) $this->operation = 'SHOW';
        elseif (strpos($upper, 'WITH') === 0) $this->operation = 'WITH';
        else $this->operation = 'UNKNOWN';
    }

    public function execute($params = null)
    {
        if ($params !== null) $this->params = $params;
        try {
            $cacheKey = $this->sql . '|' . json_encode($this->params);
            if ($this->operation === 'SELECT' && isset(self::$requestCache[$cacheKey])) {
                $this->result = self::$requestCache[$cacheKey];
                $this->rowIndex = 0;
                return true;
            }
            if (in_array($this->operation, ['INSERT', 'UPDATE', 'DELETE'])) {
                self::$requestCache = [];
            }
            $this->result = $this->doRequest();
            $this->rowIndex = 0;
            if ($this->operation === 'SELECT') {
                self::$requestCache[$cacheKey] = $this->result;
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fetch($mode = PDO::FETCH_ASSOC)
    {
        if ($this->result === null) return null;
        if ($this->rowIndex >= count($this->result)) return null;
        $row = $this->result[$this->rowIndex];
        $this->rowIndex++;
        return $row;
    }

    public function fetchAll($mode = PDO::FETCH_ASSOC)
    {
        return $this->result ?? [];
    }

    public function fetchColumn($column = 0)
    {
        if (empty($this->result)) return false;
        $row = $this->result[0] ?? [];
        $values = array_values($row);
        return isset($values[$column]) ? $values[$column] : false;
    }

    public function rowCount()
    {
        return $this->rowCount;
    }

    private function getTable()
    {
        if ($this->tableName) return $this->tableName;
        $sql = preg_replace('/\s+RETURNING\s+.*$/i', '', $this->sql);
        if (preg_match('/\bFROM\s+(?:ONLY\s+)?["\']?(\w+)["\']?/i', $sql, $m)) $this->tableName = $m[1];
        elseif (preg_match('/\bINSERT\s+INTO\s+["\']?(\w+)["\']?/i', $sql, $m)) $this->tableName = $m[1];
        elseif (preg_match('/\bUPDATE\s+["\']?(\w+)["\']?/i', $sql, $m)) $this->tableName = $m[1];
        elseif (preg_match('/\bDELETE\s+FROM\s+["\']?(\w+)["\']?/i', $sql, $m)) $this->tableName = $m[1];
        return $this->tableName;
    }

    private function parseWhere($where)
    {
        $params = [];
        if (preg_match_all('/(\w+)\s+IN\s*\(([^)]+)\)/i', $where, $inM, PREG_SET_ORDER)) {
            foreach ($inM as $im) {
                $params[$im[1]] = 'in.(' . trim($im[2]) . ')';
            }
            $where = preg_replace('/\w+\s+IN\s*\([^)]+\)/i', '', $where);
        }
        $parts = preg_split('/\b(AND|OR)\b/i', $where);
        foreach ($parts as $part) {
            $part = trim($part);
            if (!$part) continue;
            if (preg_match('/^(\w+)\s+IS\s+(NOT\s+)?NULL$/i', $part, $m)) {
                $params[$m[1]] = empty($m[2]) ? 'is.null' : 'is.not.null';
                continue;
            }
            // :named param
            if (preg_match('/^(\w+)\s*(=|!=|<>|>=|<=|>|<| LIKE | ILIKE )\s*:(\w+)\s*$/i', $part, $m)) {
                $op = trim($m[2]);
                $v = isset($this->params[':' . $m[3]]) ? $this->params[':' . $m[3]] : (isset($this->params[$m[3]]) ? $this->params[$m[3]] : null);
                if ($v !== null) $params[$m[1]] = ($this->opMap($op)) . '.' . $v;
                continue;
            }
            // literal string
            if (preg_match("/^(\w+)\s*(=|!=|<>|>=|<=|>|<| LIKE | ILIKE )\s*'([^']*)'\s*$/i", $part, $m)) {
                $params[$m[1]] = ($this->opMap(trim($m[2]))) . '.' . $m[3];
                continue;
            }
            // number
            if (preg_match('/^(\w+)\s*(=|!=|<>|>=|<=|>|<)\s*(\d+)\s*$/i', $part, $m)) {
                $params[$m[1]] = ($this->opMap(trim($m[2]))) . '.' . $m[3];
                continue;
            }
        }
        return $params;
    }

    private function opMap($op)
    {
        $map = ['=' => 'eq', '!=' => 'neq', '<>' => 'neq', '>=' => 'gte', '<=' => 'lte', '>' => 'gt', '<' => 'lt', 'LIKE' => 'like', 'ILIKE' => 'ilike'];
        return isset($map[$op]) ? $map[$op] : 'eq';
    }

    private function doRequest()
    {
        $apiUrl = $this->pdo->getApiUrl();
        $apiKey = $this->pdo->getApiKey();
        $method = 'GET';
        $url = $apiUrl;
        $jsonBody = null;

        $table = $this->getTable();
        if (!$table && $this->operation !== 'SHOW') {
            throw new RuntimeException('Cannot parse table name from SQL');
        }

        switch ($this->operation) {
            case 'SELECT':
            case 'WITH':
                if (preg_match('/\bJOIN\s+/i', $this->sql)) {
                    return $this->handleJoin($apiUrl, $apiKey);
                }
                $q = [];
                $isCount = preg_match('/\bCOUNT\s*\(\s*(?:\*|\w+)\s*\)/i', $this->sql);
                // Extract columns
                $sel = '*';
                if (!$isCount && preg_match('/SELECT\s+(.+?)\s+FROM/is', $this->sql, $m)) {
                    $sel = trim($m[1]);
                    $sel = preg_replace('/\w+\.\*/', '*', $sel);
                    $sel = preg_replace('/\w+\.(\w+)\s+AS\s+(\w+)/i', '$1:$2', $sel);
                }
                if ($isCount) { $q['select'] = 'id'; $q['limit'] = '1'; }
                else { $q['select'] = $sel; }
                // WHERE
                if (preg_match('/\bWHERE\s+(.+?)(?:\bORDER\s+BY\b|\bLIMIT\b|\bOFFSET\b|\bGROUP\s+BY\b|$)/is', $this->sql, $wm)) {
                    $conds = $this->parseWhere(trim($wm[1]));
                    foreach ($conds as $k => $v) $q[$k] = $v;
                }
                if (preg_match('/\bORDER\s+BY\s+(.+?)(?:\bLIMIT\b|\bOFFSET\b|$)/is', $this->sql, $om)) {
                    $ord = trim($om[1]);
                    $ord = str_ireplace(' ASC', '.asc', $ord);
                    $ord = str_ireplace(' DESC', '.desc', $ord);
                    $q['order'] = $ord;
                }
                if (preg_match('/\bLIMIT\s+(\d+)/i', $this->sql, $lm)) $q['limit'] = $lm[1];
                if (preg_match('/\bOFFSET\s+(\d+)/i', $this->sql, $om)) $q['offset'] = $om[1];
                $url .= $table . '?' . http_build_query($q);
                break;

            case 'INSERT':
                $sqlNoReturn = preg_replace('/\s+RETURNING\s+.*$/i', '', $this->sql);
                if (preg_match('/INSERT\s+INTO\s+(\w+)\s*\(([^)]+)\)\s*VALUES\s*\(([^)]+)\)/is', $sqlNoReturn, $m)) {
                    $cols = array_map('trim', explode(',', $m[2]));
                    $phs = array_map('trim', explode(',', $m[3]));
                    $data = [];
                    foreach ($cols as $i => $col) {
                        $ph = $phs[$i];
                        $val = null;
                        if (strpos($ph, ':') === 0) {
                            $pk = substr($ph, 1);
                            $val = isset($this->params[$ph]) ? $this->params[$ph] : (isset($this->params[$pk]) ? $this->params[$pk] : null);
                        } elseif (strpos($ph, "'") === 0) {
                            $val = substr($ph, 1, -1);
                        } elseif (is_numeric($ph)) {
                            $val = $ph + 0;
                        } elseif (strtoupper($ph) === 'NULL') {
                            $val = null;
                        } else {
                            $val = $ph;
                        }
                        if ($val !== null) $data[$col] = $val;
                    }
                    $method = 'POST';
                    $jsonBody = json_encode($data);
                    $url .= $table;
                    if (preg_match('/RETURNING\s+(\w+)/i', $this->sql, $rm)) {
                        $url .= '?select=' . $rm[1];
                    }
                }
                break;

            case 'UPDATE':
                $sqlNoReturn = preg_replace('/\s+RETURNING\s+.*$/i', '', $this->sql);
                if (preg_match('/SET\s+(.+?)\bWHERE\b/is', $sqlNoReturn, $m)) {
                    $data = [];
                    foreach (explode(',', trim($m[1])) as $set) {
                        if (preg_match('/(\w+)\s*=\s*(?::(\w+)|\'([^\']*)\'|(\d+))/i', trim($set), $sm)) {
                            $v = null;
                            if (!empty($sm[2])) $v = isset($this->params[':' . $sm[2]]) ? $this->params[':' . $sm[2]] : (isset($this->params[$sm[2]]) ? $this->params[$sm[2]] : null);
                            elseif (isset($sm[3])) $v = $sm[3];
                            elseif (isset($sm[4])) $v = $sm[4];
                            if ($v !== null) $data[$sm[1]] = $v;
                        }
                    }
                    $method = 'PATCH';
                    $jsonBody = json_encode($data);
                    if (preg_match('/\bWHERE\s+(.+?)$/is', $sqlNoReturn, $wm)) {
                        $url .= $table . '?' . http_build_query($this->parseWhere(trim($wm[1])));
                    } else {
                        $url .= $table;
                    }
                }
                break;

            case 'DELETE':
                $sqlNoReturn = preg_replace('/\s+RETURNING\s+.*$/i', '', $this->sql);
                $method = 'DELETE';
                $url .= $table;
                if (preg_match('/\bWHERE\s+(.+?)$/is', $sqlNoReturn, $wm)) {
                    $url .= '?' . http_build_query($this->parseWhere(trim($wm[1])));
                }
                break;

            case 'SHOW':
                return [];

            default:
                throw new RuntimeException('Unsupported SQL: ' . $this->operation);
        }

        $ch = curl_init();
        $headers = [
            'apikey: ' . $apiKey,
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $isCount = preg_match('/\bCOUNT\s*\(\s*(?:\*|\w+)\s*\)/i', $this->sql);
        if ($isCount) {
            $headers[] = 'Prefer: count=exact';
        } elseif (in_array($this->operation, ['INSERT', 'UPDATE', 'DELETE'])) {
            $headers[] = 'Prefer: return=representation';
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($jsonBody) curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        } elseif ($method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if ($jsonBody) curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        // Headers for Content-Range
        $respHeaders = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $h) use (&$respHeaders) {
            $len = strlen($h);
            if (stripos($h, 'content-range:') === 0) {
                $respHeaders['content-range'] = trim(substr($h, 14));
            }
            return $len;
        });

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) throw new RuntimeException('cURL error: ' . $error);

        // Success
        if (in_array($httpCode, [200, 201, 204, 206])) {
            if ($httpCode === 204) { $this->rowCount = 1; return []; }
            if (!empty($respHeaders['content-range']) && preg_match('/\/(\d+)$/', $respHeaders['content-range'], $m)) {
                $this->rowCount = (int)$m[1];
                return [['count' => (string)$m[1]]];
            }
            $decoded = json_decode($response, true);
            if (!is_array($decoded)) return [];
            if (!empty($decoded) && isset($decoded[0])) {
                $this->rowCount = count($decoded);
                return $decoded;
            }
            if (!empty($decoded)) {
                $this->rowCount = 1;
                return [$decoded];
            }
            return [];
        }

        $msg = json_decode($response, true);
        $errMsg = is_array($msg) ? (isset($msg['message']) ? $msg['message'] : $response) : $response;
        throw new RuntimeException("Supabase API error ($httpCode): " . substr((string)$errMsg, 0, 200));
    }

    private function handleJoin($apiUrl, $apiKey)
    {
        // Try to extract main table and build embedded query
        $table = $this->getTable();
        $sel = '*';
        if (preg_match('/SELECT\s+(.+?)\s+FROM/is', $this->sql, $m)) {
            $sel = trim($m[1]);
            $sel = preg_replace('/\w+\.\*/', '*', $sel);
            // Try to map u.name AS assigned_name → embedded
            if (preg_match('/(\w+)\.(\w+)\s+AS\s+(\w+)/i', $sel)) {
                // remove the aliased column, we'll get the embedded data
                $sel = '*';
            } else {
                $sel = '*';
            }
        }
        $q = ['select' => $sel];
        if (preg_match('/\bWHERE\s+(.+?)(?:\bORDER\s+BY\b|\bLIMIT\b|$)/is', $this->sql, $wm)) {
            $conds = $this->parseWhere(trim($wm[1]));
            foreach ($conds as $k => $v) $q[$k] = $v;
        }
        if (preg_match('/\bORDER\s+BY\s+(.+?)(?:\bLIMIT\b|$)/is', $this->sql, $om)) {
            $ord = trim($om[1]);
            $ord = str_ireplace(' ASC', '.asc', $ord);
            $ord = str_ireplace(' DESC', '.desc', $ord);
            $ord = preg_replace('/\w+\.(\w+)/', '$1', $ord);
            $q['order'] = $ord;
        }
        if (preg_match('/\bLIMIT\s+(\d+)/i', $this->sql, $lm)) $q['limit'] = $lm[1];
        $url = $apiUrl . $table . '?' . http_build_query($q);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['apikey: ' . $apiKey, 'Authorization: Bearer ' . $apiKey, 'Accept: application/json'],
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) throw new RuntimeException("Supabase API error ($httpCode) on join query");

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) return [];

        // Flatten embedded resources
        $flat = [];
        foreach ($decoded as $row) {
            $r = [];
            foreach ($row as $k => $v) {
                if (is_array($v) && isset($v[0]) && is_array($v[0])) {
                    foreach ($v[0] as $sk => $sv) $r[$sk] = $sv;
                } elseif (!is_array($v)) {
                    $r[$k] = $v;
                }
            }
            $flat[] = $r;
        }
        $this->rowCount = count($flat);
        return $flat;
    }
}


class SupabasePDO
{
    private $apiUrl;
    private $apiKey;

    public function __construct($apiUrl, $apiKey)
    {
        $this->apiUrl = rtrim($apiUrl, '/') . '/';
        $this->apiKey = $apiKey;
    }

    public function prepare($sql)
    {
        return new SupabaseStatement($this, $sql);
    }

    public function query($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function exec($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function lastInsertId()
    {
        return '0';
    }

    public function getApiUrl() { return $this->apiUrl; }
    public function getApiKey() { return $this->apiKey; }
}
