<?php

namespace Voting\Infrastructure;

use Voting\Migration\CreateNominationsTable;
use Voting\Migration\CreateAwardsTable;
use Voting\Migration\CreateCategoryTable;
use Voting\Migration\CreateAwardCategoryTable;
use Voting\Migration\CreateAwardFinalistsTable;
use Voting\Migration\CreateVotesTable;
use Voting\Migration\CreateWinnersTable;
use Voting\Migration\NominationsTable_2017_11_23;
use Voting\Migration\CreateWinnersMetaTable;
use Voting\Migration\NominationsTableBatchFieldType;
use Voting\Migration\VotesTableAwardId;
use Voting\Migration\NominationsTableRemoveBatchField;

/**
 * Migrates database tables 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Migrate
{
    const TABLE_PREFIX = 'v_';

    public static function prefixedTableName($tableName)
    {
        return self::TABLE_PREFIX . $tableName;
    }

    public static function tables()
    {
        CreateNominationsTable::databaseUp();
        CreateAwardsTable::databaseUp();
        CreateCategoryTable::databaseUp();
        CreateAwardCategoryTable::databaseUp();
        CreateAwardFinalistsTable::databaseUp();
        CreateWinnersTable::databaseUp();
        CreateVotesTable::databaseUp();
        NominationsTable_2017_11_23::databaseUp();
        CreateWinnersMetaTable::databaseUp();
        NominationsTableBatchFieldType::databaseUp();
        VotesTableAwardId::databaseUp();
        NominationsTableRemoveBatchField::databaseUp();
    }
}