<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePendaftaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pelatihan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'nomor_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 13,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Terdaftar', 'Dibatalkan'],
                'default'    => 'Terdaftar',
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

        // Foreign key: ON UPDATE CASCADE, ON DELETE RESTRICT
        $this->forge->addForeignKey('pelatihan_id', 'pelatihan', 'id', 'CASCADE', 'RESTRICT', 'fk_pendaftaran_pelatihan');

        // Indexes for performance
        $this->forge->addKey('pelatihan_id', false, false, 'idx_pendaftaran_pelatihan');
        $this->forge->addKey('email', false, false, 'idx_pendaftaran_email');
        $this->forge->addKey('status', false, false, 'idx_pendaftaran_status');

        $this->forge->createTable('pendaftaran', false, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('pendaftaran', true);
    }
}
