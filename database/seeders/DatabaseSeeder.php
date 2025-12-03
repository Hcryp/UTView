<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'Admin',
            'name' => 'admin@example.com',
            'password' => bcrypt('admin'), // Secure password hashing
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. Active Manpower Data (32 Records)
        DB::table('active_manpowers')->insert([
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'80101008', 'name'=>'YOSEF SUGIANTO', 'department'=>'SERVICE', 'position'=>'SUPERVISOR', 'start_contract'=>'2001-03-01', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>24, 'manhours'=>16853298024],
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'80101036', 'name'=>'GAYUS SENOAJI', 'department'=>'ADMINISTRATION', 'position'=>'SITE OPERATION HEAD', 'start_contract'=>'2001-04-06', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>21, 'manhours'=>14746635771],
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'82107136', 'name'=>'ZAINI', 'department'=>'SERVICE', 'position'=>'SUPERVISOR', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'83110040', 'name'=>'YUSUF', 'department'=>'SERVICE', 'position'=>'SUPERVISOR', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ERM', 'nrp'=>'70325002', 'name'=>'HARUN FADILLAH', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'start_contract'=>'2025-02-13', 'end_contract'=>'2025-04-30', 'effective_days'=>25, 'manhours'=>17555518775],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ERM', 'nrp'=>'70322020', 'name'=>'BAHA UDDIN', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'start_contract'=>'2025-01-01', 'end_contract'=>'2025-06-30', 'effective_days'=>20, 'manhours'=>14044415020],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT KANITRA MITRA JAYA UTAMA', 'nrp'=>'702230107', 'name'=>'A\'AN WAHYU SYAIFUDIN', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'start_contract'=>'2023-02-06', 'end_contract'=>'2025-02-06', 'effective_days'=>11, 'manhours'=>7724428261],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT KANITRA MITRA JAYA UTAMA', 'nrp'=>'702220751', 'name'=>'ABDUL HARIS', 'department'=>'ADMINISTRATION', 'position'=>'Admin', 'start_contract'=>'2022-07-08', 'end_contract'=>'2025-07-06', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT SERASI AUTORAYA (TRAC)', 'nrp'=>'80109682', 'name'=>'CHANDRA TOMMI LEKES', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>27, 'manhours'=>18959960277],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT SERASI AUTORAYA (TRAC)', 'nrp'=>'80108320', 'name'=>'MAULIDIN', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'2025-01-01', 'end_contract'=>'2025-03-31', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT SERASI AUTORAYA (TRAC)', 'nrp'=>'80107189', 'name'=>'MUHAMMAD HAWARI', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'2025-01-01', 'end_contract'=>'2025-03-31', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT SERASI AUTORAYA (TRAC)', 'nrp'=>'80109997', 'name'=>'AL IMRAN', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT DAYA MITRA SERASI', 'nrp'=>'2200004526', 'name'=>'ZAFI AHMAD AL ARIF', 'department'=>'PARTS', 'position'=>'PDC', 'start_contract'=>'2025-03-01', 'end_contract'=>'2025-12-31', 'effective_days'=>17, 'manhours'=>11937752767],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT DAYA MITRA SERASI', 'nrp'=>'2200004523', 'name'=>'AHMAD FIRDAUS', 'department'=>'PARTS', 'position'=>'PDC', 'start_contract'=>'2025-03-01', 'end_contract'=>'2025-12-31', 'effective_days'=>23, 'manhours'=>16151077273],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT GLOBAL SERVICE INDONESIA', 'nrp'=>'90125331', 'name'=>'MEYDIMAS TRIATMOKO', 'department'=>'ADMINISTRATION', 'position'=>'IT OFFICER', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT GLOBAL SERVICE INDONESIA', 'nrp'=>'92619183', 'name'=>'BURHANUDDIN', 'department'=>'SERVICE', 'position'=>'ADM SERVICE', 'start_contract'=>'Januari 2014', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>23, 'manhours'=>16151077273],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT PETROLAB', 'nrp'=>'210014', 'name'=>'KADEK SURYANI', 'department'=>'SERVICE', 'position'=>'ADM SERVICE', 'start_contract'=>'19 Maret 2021', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>23, 'manhours'=>16151077273],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR GRUP ASTRA', 'company'=>'PT HARMONI MITRA UTAMA', 'nrp'=>'2276', 'name'=>'BUDI NURDIANSYAH', 'department'=>'PARTS', 'position'=>'WHS OFFICER', 'start_contract'=>'1 januari 2011', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>21, 'manhours'=>14746635771],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'CV 99 MOTOR', 'nrp'=>'99076', 'name'=>'MUHAMMAD FAJERI', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'2025-05-01', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT LIVAN KARYA', 'nrp'=>'990518044', 'name'=>'M. FIKRI DARUSSALAM', 'department'=>'PARTS', 'position'=>'COP', 'start_contract'=>'2018-10-01', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>21, 'manhours'=>14746635771],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT LIVAN KARYA', 'nrp'=>'990521048', 'name'=>'MUHAMMAD AMIN', 'department'=>'SERVICE', 'position'=>'FACILITY OFFICER', 'start_contract'=>'2025-01-01', 'end_contract'=>'2025-12-31', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT MITRA BAKTI UT', 'nrp'=>'53323010', 'name'=>'M IRWAN', 'department'=>'SERVICE', 'position'=>'DRIVER', 'start_contract'=>'2023-12-11', 'end_contract'=>'2026-03-31', 'effective_days'=>20, 'manhours'=>14044415020],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT MITRA BAKTI UT', 'nrp'=>'53324232', 'name'=>'MAKIYATUL MAULIDIAH', 'department'=>'ADMINISTRATION', 'position'=>'OFFICE GIRL', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'UT SCHOOL', 'nrp'=>'', 'name'=>'RIFQI ISYA NURRHOIM', 'department'=>'SERVICE', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>20, 'manhours'=>14044415020],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'UMJ', 'nrp'=>'', 'name'=>'M NABIL NASHRULOH', 'department'=>'SERVICE', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'SMKN 1 SATUI', 'nrp'=>'', 'name'=>'VERELYO G.P', 'department'=>'SERVICE', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'POLTEKBA', 'nrp'=>'', 'name'=>'NOOR FEBRYANSYAH NUGRAHA', 'department'=>'SERVICE', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'POLTEKBA', 'nrp'=>'', 'name'=>'FILEMON O. P.', 'department'=>'SERVICE', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'MAGANG/PKL/OJT/UT SCHOOL', 'company'=>'ULM', 'nrp'=>'', 'name'=>'ARMITA PUTRI', 'department'=>'ADMINISTRATION', 'position'=>'MAGANG', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'R716419', 'name'=>'EDI SUCIPTO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'start_contract'=>'01.09.2014', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742785', 'name'=>'IMAM WAHONO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'start_contract'=>'01 Februari 2015', 'end_contract'=>'KARYAWAN TETAP', 'effective_days'=>22, 'manhours'=>15448856522],
            ['site'=>'', 'worker_category'=>'', 'company'=>'', 'nrp'=>'', 'name'=>'', 'department'=>'', 'position'=>'', 'start_contract'=>'', 'end_contract'=>'', 'effective_days'=>0, 'manhours'=>0]
        ]);

        // 2. Inactive Manpower Data (26 Records)
        DB::table('inactive_manpowers')->insert([
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'80111421', 'name'=>'PUNGKAS SINANGGUH', 'department'=>'SERVICE', 'position'=>'SDH', 'date_out'=>'2025-02-28', 'reason'=>'MUTASI'],
            ['site'=>'SATUI', 'worker_category'=>'KARYAWAN', 'company'=>'PT UNITED TRACTORS TBK', 'nrp'=>'80119013', 'name'=>'HIZRIAN RAJIV SADEWA', 'department'=>'SERVICE', 'position'=>'SDH', 'date_out'=>'2025-02-28', 'reason'=>'MUTASI'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT LIVAN KARYA', 'nrp'=>'990523112', 'name'=>'LUKMAN NOR HAKIM', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'date_out'=>'2025-02-28', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT LIVAN KARYA', 'nrp'=>'990523100', 'name'=>'AHMAD RIFANI', 'department'=>'SERVICE', 'position'=>'TOOLS KEEPER', 'date_out'=>'2025-02-28', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'CV 99 MOTOR', 'nrp'=>'99015', 'name'=>'ILHAM YAZID', 'department'=>'SERVICE', 'position'=>'DRIVER', 'date_out'=>'2025-01-31', 'reason'=>'HABIS KONTRAK'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT MITRA BAKTI UT', 'nrp'=>'53323008', 'name'=>'M. NURDANI', 'department'=>'SERVICE', 'position'=>'DRIVER', 'date_out'=>'2025-03-12', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17865226', 'name'=>'JOKO PRASETYO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742816', 'name'=>'PURWANTO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742797', 'name'=>'MUHAMAD JUMERI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742784', 'name'=>'ILHAM FAJRI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742828', 'name'=>'SURYADI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742777', 'name'=>'DARMAJI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742793', 'name'=>'MUHAMAD ARIFIN', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'R759169', 'name'=>'DIDIK HARI PURNOMO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17866763', 'name'=>'AHMADI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742778', 'name'=>'DENY IRAWAN', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'R716421', 'name'=>'JONI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'R716422', 'name'=>'JURAIMI', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17742792', 'name'=>'M. IRWANTO', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ISS', 'nrp'=>'17871146', 'name'=>'HASANUDIN', 'department'=>'ADMINISTRATION', 'position'=>'SECURITY', 'date_out'=>'2025-01-20', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ERM', 'nrp'=>'70324009', 'name'=>'DAYU MASRUDI PILLIANTO', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'date_out'=>'31-Mar-25', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT ERM', 'nrp'=>'50124020', 'name'=>'JIMMY  P LONDA', 'department'=>'SERVICE', 'position'=>'LEADER', 'date_out'=>'31-Mar-25', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT GLOBAL SERVICE INDONESIA', 'nrp'=>'90124072', 'name'=>'AULA MINA', 'department'=>'SERVICE', 'position'=>'ADM SERVICE', 'date_out'=>'30-Apr-25', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT KANITRA MITRA JAYA UTAMA', 'nrp'=>'702220180', 'name'=>'JUHAIRIADIN', 'department'=>'SERVICE', 'position'=>'MEKANIK', 'date_out'=>'31 Agustus 2025', 'reason'=>'RESIGN'],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT MITRA BAKTI UT', 'nrp'=>'53325193', 'name'=>'MUHAMMAD FIRDAUS', 'department'=>'ADMINISTRATION', 'position'=>'CARPENTER', 'date_out'=>'', 'reason'=>''],
            ['site'=>'SATUI', 'worker_category'=>'KONTRAKTOR NON GRUP ASTRA', 'company'=>'PT MITRA BAKTI UT', 'nrp'=>'53324232', 'name'=>'MAKIYATUL MAULIDIAH', 'department'=>'ADMINISTRATION', 'position'=>'OFFICE GIRL', 'date_out'=>'', 'reason'=>'']
        ]);

        
    }
}