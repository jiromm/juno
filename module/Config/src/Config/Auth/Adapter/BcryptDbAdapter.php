<?php

namespace Config\Auth\Adapter;

use Config\Helper\Utils;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Zend\Authentication\Adapter\DbTable;
use Zend\Db\Sql;
use Zend\Db\Sql\Predicate\Operator as SqlOp;
use Zend\Debug\Debug;

class BcryptDbAdapter extends DbTable
{
    /**
     * _authenticateCreateSelect() - This method creates a Zend\Db\Sql\Select object that
     * is completely configured to be queried against the database.
     *
     * @return Sql\Select
     */
    protected function authenticateCreateSelect()
    {
        // get select
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->tableName)
            ->columns(array('*'))
            ->where(new SqlOp($this->identityColumn, '=', $this->identity));

        return $dbSelect;
    }

    /**
     * _authenticateQuerySelect() - This method accepts a Zend\Db\Sql\Select object and
     * performs a query against the database with that object.
     *
     * @param  Sql\Select $dbSelect
     * @throws \RuntimeException when an invalid select object is encountered
     * @return array
     */
    protected function authenticateQuerySelect(Sql\Select $dbSelect)
    {
        $sql = new Sql\Sql($this->zendDb);
        $statement = $sql->prepareStatementForSqlObject($dbSelect);

        try {
            $result = $statement->execute();
            $resultIdentities = array();

            // iterate result, most cross platform way
            foreach ($result as $row) {
	            if (Utils::verify($this->credential, $row[$this->credentialColumn])) {
		            $row['zend_auth_credential_match'] = 1;
		            $resultIdentities[] = $row;
	            } else {
		            throw new AccessDeniedException('Bad password.');
	            }
            }

        } catch (\Exception $e) {
            throw new \RuntimeException(
                'The supplied parameters to DbTable failed to '
                    . 'produce a valid sql statement, please check table and column names '
                    . 'for validity.', 0, $e
            );
        }

        return $resultIdentities;
    }
}
