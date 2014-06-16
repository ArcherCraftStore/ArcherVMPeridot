<?php
//
// Pear DB LDAP - Database independent query interface definition
// for PHP's LDAP extension.
//
// Copyright (c) 2002-2003 Ludovico Magnocavallo <ludo@sumatrasolutions.com>
//
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
//
// Contributors
// - Piotr Roszatycki <dexter@debian.org>
//   DB_ldap::base() method, support for LDAP sequences, various fixes
// - Aaron Spencer Hawley <aaron dot hawley at uvm dot edu>
//   fix to use port number if present in DB_ldap->connect()
//
// $Id$
//

require_once 'DB.php';
require_once 'DB/common.php';

define("DB_ERROR_BIND_FAILED",     -26);
define("DB_ERROR_UNKNOWN_LDAP_ACTION",     -27);

/**
 * LDAP result class
 *
 * LDAP_result extends DB_result to provide specific LDAP
 * result methods.
 *
 * @version 1.0
 * @author Ludovico Magnocavallo <ludo@sumatrasolutions.com>
 * @package DB
 */

class LDAP_result extends DB_result
{

    // {{{ properties

    /**
     * data returned from ldap_entries()
     * @access private
     */
    var $_entries   = null;
    /**
     * result rows as hash of records
     * @access private
     */
    var $_recordset = null;
    /**
     * current record as hash
     * @access private
     */
    var $_record    = null;

    // }}}
    // {{{ constructor

    /**
     * class constructor, calls DB_result constructor
     * @param ref $dbh reference to the db instance
     * @param resource $result ldap command result
     */
    function LDAP_result(&$dbh, $result)
    {
        $this->DB_result($dbh, $result);
    }

    /**
     * fetch rows of data into $this->_recordset
     *
     * called once as soon as something needs to be returned
     * @access private
     * @param resource $result ldap command result
     * @return boolean true
     */
    function getRows() {
        if ($this->_recordset === null) {
            // begin processing result into recordset
            $this->_entries = ldap_get_entries($this->dbh->connection, $this->result);
            $this->row_counter = $this->_entries['count'];
            $i = 1;
            $rs_template = array();
            if (count($this->dbh->attributes) > 0) {
                reset($this->dbh->attributes);
                while (list($a_index, $a_name) = each($this->dbh->attributes)) $rs_template[$a_name] = '';
            }
            while (list($entry_idx, $entry) = each($this->_entries)) {
                // begin first loop, iterate through entries
                if (!empty($this->dbh->limit_from) && ($i < $this->dbh->limit_from)) continue;
                if (!empty($this->dbh->limit_count) && ($i > $this->dbh->limit_count)) break;
                $rs = $rs_template;
                if (!is_array($entry)) continue;
                while (list($attr, $attr_values) = each($entry)) {
                    // begin second loop, iterate through attributes
                    if (is_int($attr) || $attr == 'count') continue;
                    if (is_string($attr_values)) $rs[$attr] = $attr_values;
                    else {
                        $value = '';
                        while (list($value_idx, $attr_value) = each($attr_values)) {
                            // begin third loop, iterate through attribute values
                            if (!is_int($value_idx)) continue;
                            if (empty($value)) $value = $attr_value;
                            else {
                                if (is_array($value)) $value[] = $attr_value;
                                else $value = array($value, $attr_value);
                            }
//                          else $value .= "\n$attr_value";
                            // end third loop
                        }
                        $rs[$attr] = $value;
                    }
                    // end second loop
                }
                reset($rs);
                $this->_recordset[$entry_idx] = $rs;
                $i++;
                // end first loop
            }
            $this->_entries = null;
            if (!is_array($this->_recordset))
                $this->_recordset = array();
            if (!empty($this->dbh->sorting)) {
                $sorting_method = (!empty($this->dbh->sorting_method) ? $this->dbh->sorting_method : 'cmp');
                uksort($this->_recordset, array(&$this, $sorting_method));
            }
            reset($this->_recordset);
            // end processing result into recordset
        }
        return DB_OK;
    }


    /**
     * Fetch and return a row of data (it uses driver->fetchInto for that)
     * @param int $fetchmode  format of fetched row
     * @param int $rownum     the row number to fetch
     *
     * @return  array a row of data, NULL on no more rows or PEAR_Error on error
     *
     * @access public
     */
    function &fetchRow($fetchmode = DB_FETCHMODE_DEFAULT, $rownum = null)
    {
        $this->getRows();
        if (count($this->_recordset) === 0 ) {
            return null;
        }

        if ($this->_record !== null) {
            $this->_record = next($this->_recordset);
        } else {
            $this->_record = current($this->_recordset);
        }
        $row = $this->_record;
        if ($row === false) {
            return null;
        }

        return $row;
    }


    /**
     * Fetch a row of data into an existing variable.
     *
     * @param  mixed     $arr        reference to data containing the row
     * @param  integer   $fetchmode  format of fetched row
     * @param  integer   $rownum     the row number to fetch
     *
     * @return  mixed  DB_OK on success, NULL on no more rows or
     *                 a DB_Error object on error
     *
     * @access public
     */

    function fetchInto(&$ar, $fetchmode = DB_FETCHMODE_DEFAULT, $rownum = null)
    {
        $this->getRows();
        if ($this->_record !== null) $this->_record = next($this->_recordset);
        else $this->_record = current($this->_recordset);
        $ar = $this->_record;
        if (!$ar) {
            return null;
        }
        return DB_OK;
    }

    /**
     * return all records
     *
     * returns a hash of all records, basically returning
     * a copy of $this->_recordset
     * @param  integer   $fetchmode  format of fetched row
     * @param  integer   $rownum     the row number to fetch (not used, here for interface compatibility)
     *
     * @return  mixed  DB_OK on success, NULL on no more rows or
     *                 a DB_Error object on error
     *
     * @access public
     */
    function fetchAll($fetchmode = DB_FETCHMODE_DEFAULT, $rownum = null)
    {
        $this->getRows();
        return($this->_recordset);
    }

    /**
     * Get the the number of columns in a result set.
     *
     * @return int the number of columns, or a DB error
     *
     * @access public
     */
    function numCols($result)
    {
        $this->getRows();
        return(count(array_keys($this->_record)));
    }

    function cmp($a, $b)
    {
        return(strcmp(strtolower($this->_recordset[$a][$this->dbh->sorting]), strtolower($this->_recordset[$b][$this->dbh->sorting])));
    }

    /**
     * Get the number of rows in a result set.
     *
     * @return int the number of rows, or a DB error
     *
     * @access public
     */
    function numRows()
    {
        $this->getRows();
        return $this->row_counter;
    }

    /**
     * Get the next result if a batch of queries was executed.
     *
     * @return bool true if a new result is available or false if not.
     *
     * @access public
     */
    function nextResult()
    {
        return $this->dbh->nextResult($this->result);
    }

    /**
     * Frees the resources allocated for this result set.
     * @return  int     error code
     *
     * @access public
     */
    function free()
    {
        $this->_recordset = null;
        $this->_record = null;
        ldap_free_result($this->result);
        $this->result = null;
        return true;
    }

    /**
    * @deprecated
    */
    function tableInfo($mode = null)
    {
        return $this->dbh->tableInfo($this->result, $mode);
    }

    /**
    * returns the actual rows number
    * @return integer
    */
    function getRowCounter()
    {
        $this->getRows();
        return $this->row_counter;
    }
}

/**
 * LDAP DB interface class
 *
 * LDAP extends DB_common to provide DB compliant
 * access to LDAP servers
 *
 * @version 1.0
 * @author Ludovico Magnocavallo <ludo@sumatrasolutions.com>
 * @package DB
 */

class DB_ldap extends DB_common
{
    // {{{ properties

    /**
     * LDAP connection
     * @access private
     */
    var $connection;
    /**
     * base dn
     * @access private
     */
    var $base           = '';
    /**
     * default base dn
     * @access private
     */
    var $d_base           = '';
    /**
     * query base dn
     * @access private
     */
    var $q_base           = '';
    /**
     * array of LDAP actions that only manipulate data
     * returning a true/false value
     * @access private
     */
    var $manip          = array('add', 'compare', 'delete', 'modify', 'mod_add', 'mod_del', 'mod_replace', 'rename');
    /**
     * store the default real LDAP action to perform
     * @access private
     */
    var $action         = 'search';
    /**
     * store the real LDAP action to perform
     * (ie PHP ldap function to call) for a query
     * @access private
     */
    var $q_action       = '';
    /**
     * store optional parameters passed
     *  to the real LDAP action
     * @access private
     */
    var $q_params       = array();

    // }}}

    /**
     * Constructor, calls DB_common constructor
     *
     * @see DB_common::DB_common()
     */
    function DB_ldap()
    {
        $this->DB_common();
        $this->phptype = 'ldap';
        $this->dbsyntax = 'ldap';
        $this->features = array(
            'prepare'       => false,
            'pconnect'      => false,
            'transactions'  => false,
            'limit'         => false
        );
        $this->errorcode_map = array(
            0x10 => DB_ERROR_NOSUCHFIELD,               // LDAP_NO_SUCH_ATTRIBUTE
            0x11 => DB_ERROR_INVALID,                   // LDAP_UNDEFINED_TYPE
            0x12 => DB_ERROR_INVALID,                   // LDAP_INAPPROPRIATE_MATCHING
            0x13 => DB_ERROR_INVALID,                   // LDAP_CONSTRAINT_VIOLATION
            0x14 => DB_ERROR_ALREADY_EXISTS,            // LDAP_TYPE_OR_VALUE_EXISTS
            0x15 => DB_ERROR_INVALID,                   // LDAP_INVALID_SYNTAX
            0x20 => DB_ERROR_NOT_FOUND,                 // LDAP_NO_SUCH_OBJECT
            0x21 => DB_ERROR_NOT_FOUND,                 // LDAP_ALIAS_PROBLEM
            0x22 => DB_ERROR_INVALID,                   // LDAP_INVALID_DN_SYNTAX
            0x23 => DB_ERROR_INVALID,                   // LDAP_IS_LEAF
            0x24 => DB_ERROR_INVALID,                   // LDAP_ALIAS_DEREF_PROBLEM
            0x30 => DB_ERROR_ACCESS_VIOLATION,          // LDAP_INAPPROPRIATE_AUTH
            0x31 => DB_ERROR_ACCESS_VIOLATION,          // LDAP_INVALID_CREDENTIALS
            0x32 => DB_ERROR_ACCESS_VIOLATION,          // LDAP_INSUFFICIENT_ACCESS
            0x40 => DB_ERROR_MISMATCH,                  // LDAP_NAMING_VIOLATION
            0x41 => DB_ERROR_MISMATCH,                  // LDAP_OBJECT_CLASS_VIOLATION
            0x44 => DB_ERROR_ALREADY_EXISTS,            // LDAP_ALREADY_EXISTS
            0x51 => DB_ERROR_CONNECT_FAILED,            // LDAP_SERVER_DOWN
            0x57 => DB_ERROR_SYNTAX                     // LDAP_FILTER_ERROR
        );
    }

    /**
     * Connect and bind to LDAP server with either anonymous or authenticated bind depending on dsn info
     *
     * @param array $dsninfo dsn info as passed by DB::connect()
     * @param boolean $persistent kept for interface compatibility
     * @return DB_OK if successfully connected. A DB error code is returned on failure.
     */
    function connect($dsninfo, $persistent = false)
    {
        if (!PEAR::loadExtension('ldap'))
            return $this->raiseError(DB_ERROR_EXTENSION_NOT_FOUND);

        $this->dsn    = $dsninfo;
        $user         = $dsninfo['username'];
        $pw           = $dsninfo['password'];
        $host         = $dsninfo['hostspec'];
        $port         = $dsninfo['port'];
        $this->base   = $dsninfo['database'];
        $this->d_base = $this->base;
        $version      = $dsninfo['protocol'];

        if (empty($host)) {
            return $this->raiseError("no host specified $host");
        } // else ...

        if (isset($port)) {
            $conn = ldap_connect($host, $port);
        } else {
            $conn = ldap_connect($host);
        }
        if (!$conn) {
            return $this->raiseError(DB_ERROR_CONNECT_FAILED);
        }

        if (isset($version)) {
            ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, $version);
        } else {
            // Use 3 by default
            $res = ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            // If 3 fails then we can try 2
            if (!$res) {
                ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 2);
            }
        }

        if ($user && $pw) {
            $bind = @ldap_bind($conn, $user, $pw);
        } else {
            $bind = @ldap_bind($conn);
        }
        if (!$bind) {
            return $this->raiseError(DB_ERROR_BIND_FAILED);
        }
        $this->connection = $conn;
        return DB_OK;
    }

    /**
     * Unbinds from LDAP server
     *
     * @return int ldap_unbind() return value
     */
    function disconnect()
    {
        $ret = @ldap_unbind($this->connection);
        $this->connection = null;
        return $ret;
    }


    /**
     * Performs a request against the LDAP server
     *
     * The type of request (and the corresponding PHP ldap function called)
     * depend on two additional parameters, added in respect to the
     * DB_common interface.
     *
     * @param string $filter text of the request to send to the LDAP server
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return result from ldap function or DB Error object if no result
     */
    function simpleQuery($filter, $action = null, $params = null)
    {
        if ($action === null) {
            $action = (!empty($this->q_action) ? $this->q_action : $this->action);
        }
        if ($params === null) {
            $params = (count($this->q_params) > 0 ? $this->q_params : array());
        }
        if (!$this->isManip($action)) {
            $base = $this->q_base ? $this->q_base : $this->base;
            $attributes = array();
            $attrsonly = 0;
            $sizelimit = 0;
            $timelimit = 0;
            $deref = LDAP_DEREF_NEVER;
            $sorting = '';
            $sorting_method = '';
            reset($params);
            while (list($k, $v) = each($params)) {
                if (isset(${$k})) ${$k} = $v;
            }
            $this->sorting = $sorting;
            $this->sorting_method = $sorting_method;
            $this->attributes = $attributes;
            # double escape char for filter: '(o=Przedsi\C4\99biorstwo)' => '(o=Przedsi\\C4\\99biorstwo)'
            $filter = str_replace('\\', '\\\\', $filter);
            $this->last_query = $filter;
            if ($action == 'search')
                $result = @ldap_search($this->connection, $base, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            else if ($action == 'list')
                $result = @ldap_list($this->connection, $base, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            else if ($action == 'read')
                $result = @ldap_read($this->connection, $base, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
            else
                return $this->ldapRaiseError(DB_ERROR_UNKNOWN_LDAP_ACTION);
            if (!$result) {
                return $this->ldapRaiseError();
            }
        } else {
            # If first argument is an array, it contains the entry with DN.
            if (is_array($filter)) {
                $entry = $filter;
                $filter = $entry["dn"];
            } else {
                $entry = array();
            }
            unset($entry["dn"]);
            $attribute      = '';
            $value          = '';
            $newrdn         = '';
            $newparent      = '';
            $deleteoldrdn   = false;
            reset($params);
            while (list($k, $v) = each($params)) {
                if (isset(${$k})) ${$k} = $v;
            }
            $this->last_query = $filter;
            if ($action == 'add')
                $result = @ldap_add($this->connection, $filter, $entry);
            else if ($action == 'compare')
                $result = @ldap_add($this->connection, $filter, $attribute, $value);
            else if ($action == 'delete')
                $result = @ldap_delete($this->connection, $filter);
            else if ($action == 'modify')
                $result = @ldap_modify($this->connection, $filter, $entry);
            else if ($action == 'mod_add')
                $result = @ldap_mod_add($this->connection, $filter, $entry);
            else if ($action == 'mod_del')
                $result = @ldap_mod_del($this->connection, $filter, $entry);
            else if ($action == 'mod_replace')
                $result = @ldap_mod_replace($this->connection, $filter, $entry);
            else if ($action == 'rename')
                $result = @ldap_rename($this->connection, $filter, $newrdn, $newparent, $deleteoldrdn);
            else
                return $this->ldapRaiseError(DB_ERROR_UNKNOWN_LDAP_ACTION);
            if (!$result) {
                return $this->ldapRaiseError();
            }
        }
        $this->freeQuery();
        return $result;
    }

    /**
     * Executes a query performing variables substitution in the query text
     *
     * @param string $stmt text of the request to send to the LDAP server
     * @param array $data query variables values to substitute
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return LDAP_result object or DB Error object if no result
     * @see DB_common::executeEmulateQuery $this->simpleQuery()
     */
    function execute($stmt, $data = false, $action = null, $params = array())
    {
        $this->q_params = $params;
        $realquery = $this->executeEmulateQuery($stmt, $data);
        if (DB::isError($realquery)) {
            return $realquery;
        }
        $result = $this->simpleQuery($realquery, $action, $params);
        if (DB::isError($result) || $result === DB_OK) {
            return $result;
        }

        $obj = new LDAP_result($this, $result);
        return $obj;
    }

    /**
     * Executes multiple queries performing variables substitution for each query
     *
     * @param string $stmt text of the request to send to the LDAP server
     * @param array $data query variables values to substitute
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return LDAP_result object or DB Error object if no result
     * @see DB_common::executeMultiple
     */
    function executeMultiple($stmt, &$data, $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        return(parent::executeMultiple($stmt, $data));
    }

    /**
     * Executes a query substituting variables if any are present
     *
     * @param string $query text of the request to send to the LDAP server
     * @param array $data query variables values to substitute
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return LDAP_result object or DB Error object if no result
     * @see DB_common::prepare() $this->execute()$this->simpleQuery()
     */
    function &query($query, $data = array(), $action = null, $params = array())
    {
        // $this->q_action = $action ? $action : $this->action;
        // $this->q_params = $params;
        if (sizeof($data) > 0) {
            $sth = $this->prepare($query);
            if (DB::isError($sth)) {
                return $sth;
            }
            return $this->execute($sth, $data);
        }

        $result = $this->simpleQuery($query, $action, $params);
        if (DB::isError($result) || $result === DB_OK) {
            return $result;
        }

        $obj = new LDAP_result($this, $result);
        return $obj;
    }

    /**
     * Modifies a query to return only a set of rows, stores $from and $count for LDAP_result
     *
     * @param string $query text of the request to send to the LDAP server
     * @param int $from record position from which to start returning data
     * @param int $count number of records to return
     * @return modified query text (no modifications are made, see above)
     */
    function modifyLimitQuery($query, $from, $count)
    {
        $this->limit_from = $from;
        $this->limit_count = $count;
        return $query;
    }

    /**
     * Executes a query returning only a specified number of rows
     *
     * This method only saves the $from and $count parameters for LDAP_result
     * where the actual records processing takes place
     *
     * @param string $query text of the request to send to the LDAP server
     * @param int $from record position from which to start returning data
     * @param int $count number of records to return
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return LDAP_result object or DB Error object if no result
     */
    function limitQuery($query, $from, $count, $action = null, $params = array())
    {
        $query = $this->modifyLimitQuery($query, $from, $count);
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        return $this->query($query, $action, $params);
    }

    /**
     * Fetch the first column of the first row of data returned from
     * a query.  Takes care of doing the query and freeing the results
     * when finished.
     *
     * @param $query the SQL query
     * @param $data if supplied, prepare/execute will be used
     *        with this array as execute parameters
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @return array
     * @see DB_common::getOne()
     * @access public
     */
    function &getOne($query, $data = array(), $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        return(parent::getOne($query, $data));
    }

    /**
     * Fetch the first row of data returned from a query.  Takes care
     * of doing the query and freeing the results when finished.
     *
     * @param $query the SQL query
     * @param $fetchmode the fetch mode to use
     * @param $data array if supplied, prepare/execute will be used
     *        with this array as execute parameters
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @access public
     * @return array the first row of results as an array indexed from
     * 0, or a DB error code.
     * @see DB_common::getRow()
     * @access public
     */
    function &getRow($query,
                     $data = null,
                     $fetchmode = DB_FETCHMODE_DEFAULT,
                     $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        return(parent::getRow($query, $data, $fetchmode));
    }

    /**
     * Fetch the first column of data returned from a query.  Takes care
     * of doing the query and freeing the results when finished.
     *
     * @param $query the SQL query
     * @param $col which column to return (integer [column number,
     * starting at 0] or string [column name])
     * @param $data array if supplied, prepare/execute will be used
     *        with this array as execute parameters
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @access public
     * @return array an indexed array with the data from the first
     * row at index 0, or a DB error code.
     * @see DB_common::getCol()
     * @access public
     */
    function &getCol($query, $col = 0, $data = array(), $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        return(parent::getCol($query, $col, $data));
    }

    /**
     * Calls DB_common::getAssoc()
     *
     * @param $query the SQL query
     * @param $force_array (optional) used only when the query returns
     * exactly two columns.  If true, the values of the returned array
     * will be one-element arrays instead of scalars.
     * starting at 0] or string [column name])
     * @param array $data if supplied, prepare/execute will be used
     *        with this array as execute parameters
     * @param $fetchmode the fetch mode to use
     * @param boolean $group see DB_Common::getAssoc()
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @access public
     * @return array an indexed array with the data from the first
     * row at index 0, or a DB error code.
     * @see DB_common::getAssoc()
     * @access public
     */
    function &getAssoc($query, $force_array = false, $data = array(),
                       $fetchmode = DB_FETCHMODE_ORDERED, $group = false,
                       $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        $result = parent::getAssoc($query, $force_array, $data, $fetchmode, $group);
        return $result;
    }

    /**
     * Fetch all the rows returned from a query.
     *
     * @param $query the SQL query
     * @param array $data if supplied, prepare/execute will be used
     *        with this array as execute parameters
     * @param $fetchmode the fetch mode to use
     * @param string $action type of request to perform, defaults to search (ldap_search())
     * @param array $params array of additional parameters to pass to the PHP ldap function requested
     * @access public
     * @return array an nested array, or a DB error
     * @see DB_common::getAll()
     */
    function &getAll($query,
                     $data = null,
                     $fetchmode = DB_FETCHMODE_DEFAULT,
                     $action = null, $params = array())
    {
        $this->q_action = $action ? $action : $this->action;
        $this->q_params = $params;
        $result = parent::getAll($query, $data, $fetchmode);
        return $result;
    }

    function numRows($result)
    {
        return $result->numRows();
    }

    function getTables()
    {
        return $this->ldapRaiseError(DB_ERROR_NOT_CAPABLE);
    }

    function getListOf($type)
    {
        return $this->ldapRaiseError(DB_ERROR_NOT_CAPABLE);
    }

    function isManip($action)
    {
        return(in_array($action, $this->manip));
    }

    function freeResult()
    {
        return true;
    }

    function freeQuery($query = '')
    {
        $this->q_action = '';
        $this->q_base   = '';
        $this->q_params = array();
        $this->attributes = null;
        $this->sorting = '';
        return true;
    }

    // Deprecated, will be removed in future releases.
    function base($base = null)
    {
        $this->q_base = ($base !== null) ? $base : null;
        return true;
    }

    function ldapSetBase($base = null)
    {
        $this->base = ($base !== null) ? $base : $this->d_base;
        $this->q_base = '';
        return true;
    }

    function ldapSetAction($action = 'search')
    {
        if ($action != 'search' && $action != 'list' && $action != 'read') {
            return $this->ldapRaiseError(DB_ERROR_UNKNOWN_LDAP_ACTION);
        }
        $this->action = $action;
        $this->q_action = '';
        return true;
    }

    /**
     * Get the next value in a sequence.
     *
     * LDAP provides transactions for only one entry and we need to
     * prevent race condition. If unique value before and after modify
     * aren't equal then wait and try again.
     *
     * The name of sequence is LDAP DN of entry.
     *
     * @access public
     * @param string $seq_name the DN of the sequence
     * @param bool $ondemand whether to create the sequence on demand
     * @return a sequence integer, or a DB error
     */
    function nextId($seq_name, $ondemand = true)
    {
        $repeat = 0;
        do {
            // Get the sequence entry
            $this->base($seq_name);
            $this->pushErrorHandling(PEAR_ERROR_RETURN);
            $data = $this->getRow("objectClass=*");
            $this->popErrorHandling();

            if (DB::isError($data)) {
                // DB_ldap doesn't use DB_ERROR_NOT_FOUND
                if ($ondemand && $repeat == 0
                && $data->getCode() == DB_ERROR) {
                // Try to create sequence and repeat
                    $repeat = 1;
                    $data = $this->createSequence($seq_name);
                    if (DB::isError($data)) {
                        return $this->ldapRaiseError($data);
                    }
                } else {
                    // Other error
                    return $this->ldapRaiseError($data);
                }
            } else {
                // Increment sequence value
                $data["cn"]++;
                // Unique identificator of transaction
                $seq_unique = mt_rand();
                $data["uid"] = $seq_unique;
                // Modify the LDAP entry
                $this->pushErrorHandling(PEAR_ERROR_RETURN);
                $data = $this->simpleQuery($data, 'modify');
                $this->popErrorHandling();
                if (DB::isError($data)) {
                    return $this->ldapRaiseError($data);
                }
                // Get the entry and check if it contains our unique value
                $this->base($seq_name);
                $data = $this->getRow("objectClass=*");
                if (DB::isError($data)) {
                    return $this->ldapRaiseError($data);
                }
                if ($data["uid"] != $seq_unique) {
                    // It is not our entry. Wait a little time and repeat
                    sleep(1);
                    $repeat = 1;
                } else {
                    $repeat = 0;
                }
            }
        } while ($repeat);

        if (DB::isError($data)) {
            return $data;
        }
        return $data["cn"];
    }

    /**
     * Create the sequence
     *
     * The sequence entry is based on core schema with extensibleObject,
     * so it should work with any LDAP server which doesn't check schema
     * or supports extensibleObject object class.
     *
     * Sequence name have to be DN started with "sn=$seq_id,", i.e.:
     *
     * $seq_name = "sn=uidNumber,ou=sequences,dc=php,dc=net";
     *
     * dn: $seq_name
     * objectClass: top
     * objectClass: extensibleObject
     * sn: $seq_id
     * cn: $seq_value
     * uid: $seq_uniq
     *
     * @param string $seq_name the DN of the sequence
     * @return mixed DB_OK on success or DB error on error
     * @access public
     */
    function createSequence($seq_name)
    {
        // Extract $seq_id from DN
        list($seq_id, $_) = explode(",", seq_name, 2);

        // Create the sequence entry
        $data = array(
            'dn' => $seq_name,
            'objectclass' => array("top", "extensibleObject"),
            'sn' => $seq_id,
            'cn' => 0,
            'uid' => 0
        );

        // Add the LDAP entry
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $data = $this->simpleQuery($data, 'add');
        $this->popErrorHandling();
        return $data;
    }

    /**
     * Drop a sequence
     *
     * @param string $seq_name the DN of the sequence
     * @return mixed DB_OK on success or DB error on error
     * @access public
     */
    function dropSequence($seq_name)
    {
        // Delete the sequence entry
        $data = array(
            'dn' => $seq_name,
        );
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $data = $this->simpleQuery($data, 'delete');
        $this->popErrorHandling();
        return $data;
    }

    // {{{ ldapRaiseError()

    function ldapRaiseError($errno = null)
    {
        if ($errno === null) {
            $errno = $this->errorCode(ldap_errno($this->connection));
        }
        if ($this->q_action !== null) {
            return $this->raiseError($errno, null, null,
                sprintf('%s base="%s" filter="%s"',
                    $this->q_action, $this->q_base, $this->last_query
                ),
                $errno == DB_ERROR_UNKNOWN_LDAP_ACTION ? null : @ldap_error($this->connection));
        } else {
            return $this->raiseError($errno, null, null, "???",
                @ldap_error($this->connection));
        }
    }

    // }}}

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
?>
