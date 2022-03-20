<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('mst_pegawai')->delete();

        $pegawai = [
            //Pegawai Gudang Batil
            ['nip' => 1, 'nama' => 'Sugiyanti', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Suwarni', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Partinah', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Tukinah', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Suyati', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Martha', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Karsiyah', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Rochati', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Karmini', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Umi Hanik', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Kasriyatun', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Eni Anggraini', 'kodeBagian' => 'batil', 'jenisKelamin' => 'P', 'userId' => 1],
            

            //Pegawai Gudang Kontrol
            ['nip' => 1, 'nama' => 'Emi F', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Trisyanti', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Suharningsih', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Supriyanti', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Nunung', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Tumini', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Sri Suharti', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Tri Mulyani', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Suparmi', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Paryatun', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Kusrini', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Sukini', 'kodeBagian' => 'kontrol', 'jenisKelamin' => 'P', 'userId' => 1],
        

            //Pegawai Gudang Potong
            ['nip' => 1, 'nama' => 'Nurjanah', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Sukiyem', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Supriyati', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Surip Lestari', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Sutarmi', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Nanik', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Zubaidah', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'T. Sumiati', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Suwarti', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Sumiyati', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Sri Watinah', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Siwarni', 'kodeBagian' => 'potong', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang Jahit Zoom
            ['nip' => 1, 'nama' => 'Istiani', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Kartini', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Sugiarti', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Juharni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Karni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Sudarti', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Darsi', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Nurul H', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Suharti', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Peni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Supriyati', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Maryati', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 13, 'nama' => 'Samiyem', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 14, 'nama' => 'Masriah', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 15, 'nama' => 'Sofiah', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 16, 'nama' => 'Siti Romiyati', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 17, 'nama' => 'Sri Winarni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 18, 'nama' => 'S P Astuti', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 19, 'nama' => 'S Wahyuni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 20, 'nama' => 'Jumilah', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 21, 'nama' => 'S Jatmiyati', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 22, 'nama' => 'Jumiatun', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 23, 'nama' => 'Sunarni', 'kodeBagian' => 'zoom', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang Jahit Jahit
            ['nip' => 1, 'nama' => 'Suparti', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Cunipah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Ngatini', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Dwi Indarti', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Surati', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Khoiriyah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Sukinah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Miarsih', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Imah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Sutarni', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Indah S', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Asnoah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 13, 'nama' => 'Kusmiyati', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 14, 'nama' => 'Suyati', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 15, 'nama' => 'Sujiati', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 16, 'nama' => 'Supiyatun', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 17, 'nama' => 'Amanah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 18, 'nama' => 'Sulastri', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 19, 'nama' => 'Jumiyati', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 20, 'nama' => 'Supatinah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 21, 'nama' => 'Nyamini', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 22, 'nama' => 'Sri Mulyani', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 23, 'nama' => 'Nanik P', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 24, 'nama' => 'Tuginingsih', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 25, 'nama' => 'Anik Y', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 26, 'nama' => 'Muyatini', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 27, 'nama' => 'Yayuk U', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 28, 'nama' => 'Baidah', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 29, 'nama' => 'Murtini', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 30, 'nama' => 'Sri Lestari', 'kodeBagian' => 'jahit', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang Jahit Bawahan
            ['nip' => 1, 'nama' => 'Nama Bawahan 1', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Nama Bawahan 2', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Nama Bawahan 3', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Nama Bawahan 4', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Nama Bawahan 5', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Nama Bawahan 6', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Nama Bawahan 7', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Nama Bawahan 8', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Nama Bawahan 9', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Nama Bawahan 10', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Nama Bawahan 11', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Nama Bawahan 12', 'kodeBagian' => 'bawahan', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang Setrika
            ['nip' => 1, 'nama' => 'Wiji Lestari', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Sukirah', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Suwarsih', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Wiwik DA', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Marji', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Maryatun', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 7, 'nama' => 'Sriyanti', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 8, 'nama' => 'Suminem', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 9, 'nama' => 'Sadinem', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 10, 'nama' => 'Eko S', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 11, 'nama' => 'Marfuatun', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 12, 'nama' => 'Suliyah', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 13, 'nama' => 'Sumirah', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 14, 'nama' => 'Kuntowati', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 15, 'nama' => 'Sumarni', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 16, 'nama' => 'Alimah', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 17, 'nama' => 'RoPmini', 'kodeBagian' => 'setrika', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang Jahit Bungkus
            ['nip' => 1, 'nama' => 'Anik Ariyani', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Yolanda', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Trimah', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Wagirah', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Eryati', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 6, 'nama' => 'Fajar', 'kodeBagian' => 'bungkus', 'jenisKelamin' => 'P', 'userId' => 1],
        
            //Pegawai Gudang 
            ['nip' => 1, 'nama' => 'Muri', 'kodeBagian' => 'gudang', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 2, 'nama' => 'Kusyanto', 'kodeBagian' => 'gudang', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 3, 'nama' => 'Lilik Utomo', 'kodeBagian' => 'gudang', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 4, 'nama' => 'Ageng', 'kodeBagian' => 'gudang', 'jenisKelamin' => 'P', 'userId' => 1],
            ['nip' => 5, 'nama' => 'Kasmuri', 'kodeBagian' => 'gudang', 'jenisKelamin' => 'P', 'userId' => 1],
        
        ];
                   
        DB::table('mst_pegawai')->insert($pegawai);
    }
}
