<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelatihanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'instruktur' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jam_mulai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'jam_selesai' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'kuota' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'sisa_kuota' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Tersedia', 'Penuh'],
                'default'    => 'Tersedia',
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable('pelatihan', false, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);

        // Add CHECK constraints via raw SQL (CI4 Forge doesn't support CHECK natively)
        $this->db->query('ALTER TABLE `pelatihan`
            ADD CONSTRAINT `chk_pelatihan_jam` CHECK (`jam_selesai` > `jam_mulai`),
            ADD CONSTRAINT `chk_pelatihan_kuota` CHECK (`kuota` BETWEEN 1 AND 100),
            ADD CONSTRAINT `chk_pelatihan_sisa_kuota` CHECK (`sisa_kuota` >= 0 AND `sisa_kuota` <= `kuota`)
        ');
    }

    public function down()
    {
        $this->forge->dropTable('pelatihan', true);
    }
}
