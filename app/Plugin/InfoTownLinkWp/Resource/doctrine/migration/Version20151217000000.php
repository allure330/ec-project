<?php
/*
 * This file is part of InfoTownLinkWp Plugin of EC-CUBE3
 *
 * copyright(c) 2015- Hiroshi Sawai All Rights Reserved.
 *
 * http://www.info-town.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151217000000 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->createWordpress($schema);
    }

    /**
     * @param Schema $schema
     * @link http://code-usage.com/v/Doctrine/DBAL/Schema/Schema:dropSequence
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('plg_infotown_linkwp_wordpress');
    }

    /**
     * Create plg_link_wordpress table used by InfoTownLinkWp only plugin install.
     * @param Schema $schema
     */
    protected function createWordpress(Schema $schema)
    {
        $table = $schema->createTable('plg_infotown_linkwp_wordpress');
        $table->addColumn(
            'id',
            'integer',
            [
                'autoincrement' => false,
                'notnull'       => true,
            ]
        );
        $table->addColumn(
            'home_url',
            'text',
            ['notnull' => false]
        );
        $table->addColumn(
            'api_url',
            'text',
            ['notnull' => false]
        );
        $table->setPrimaryKey(['id']);
    }
}