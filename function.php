<?php

session_start();

$c = mysqli_connect('localhost','root','','zakat');

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check = mysqli_query($c,"SELECT * FROM user WHERE username='$username' and password='$password' ");
    $hitung = mysqli_num_rows($check);
    if($hitung>0){

        $_SESSION['login'] = 'True';
        header('location:index.php');
    } else {
        echo '
        <script> alert ("Username atau Password salah");
        window.location.href="login.php"
        </script>
        ';
    }
}

if(isset($_POST['tambahmuzakki'])){
    $muzakki            = $_POST['muzakki'];
    $jumlahtanggungan   = $_POST['jumlahtanggungan'];
    $keterangan         = $_POST['keterangan'];

    $tambah = mysqli_query($c,"insert into muzakki (nama_muzakki,jumlah_tanggungan,keterangan) values ('$muzakki','$jumlahtanggungan','$keterangan')");

    if($tambah){
        header('location:muzakki.php');
    } else {
        echo '
        <script> alert ("Gagal menambah data baru");
        window.location.href="muzakki.php"
        </script>
        ';
    }

}
if(isset($_POST['editmuzakki'])){
    $edit = mysqli_query($c,"update muzakki set nama_muzakki = '$_POST[muzakki]', jumlah_tanggungan = '$_POST[jt]', keterangan = '$_POST[keterangan]'  where id_muzakki = '$_POST[editidmuzakki]'");

    if($edit ){
        header('location:muzakki.php');
    } else {
        echo '
        <script> alert ("Gagal mengedit data ini");
        window.location.href="muzakki.php"
        </script>
        ';
    }

}

if(isset($_POST['hapusmuzakki'])){
    $idmuzakki1           = $_POST['idmuzakki1'];

    $hapus = mysqli_query($c,"delete from muzakki where id_muzakki = '$idmuzakki1'");
    $hapus_bayarzakat = mysqli_query($c, "delete FROM bayarzakat WHERE id_muzakki = '$idmuzakki1'");

    if($hapus && $hapus_bayarzakat){
        header('location:muzakki.php');
    } else {
        echo '
        <script> alert ("Gagal menghapus data ini");
        window.location.href="muzakki.php"
        </script>
        ';
    }

}

if(isset($_POST['tambahmustahik'])){
    $kategori   = $_POST['kategori'];
    $jumlahhak  = $_POST['jumlahhak'];

    $tambah = mysqli_query($c,"insert into kategori_mustahik (nama_kategori,jumlah_hak) values ('$kategori','$jumlahhak')");

    if($tambah){
        header('location:mustahik.php');
    } else {
        echo '
        <script> alert ("Gagal menambah data baru");
        window.location.href="mustahik.php"
        </script>
        ';
    }

}
if(isset($_POST['hapusmustahik'])){
    $idmustahik          = $_POST['idmustahik'];

    $hapus = mysqli_query($c,"delete from kategori_mustahik where id_kategori = '$idmustahik'");

    if($hapus ){
        header('location:mustahik.php');
    } else {
        echo '
        <script> alert ("Gagal menghapus data ini");
        window.location.href="mustahik.php"
        </script>
        ';
    }

}
if(isset($_POST['editmustahik'])){
    $editidkategori   = $_POST['editidkategori'];
    $mustahik   = $_POST['mustahik'];
    $jumlahhak   = $_POST['jumlahhak'];

    $edit = mysqli_query($c,"update kategori_mustahik set nama_kategori = '$mustahik', jumlah_hak = '$jumlahhak'  where id_kategori = '$editidkategori'");

    if($edit ){
        header('location:mustahik.php');
    } else {
        echo '
        <script> alert ("Gagal mengedit data ini");
        window.location.href="mustahik.php"
        </script>
        ';
    }

}

if(isset($_POST['bayarzakat'])){
    $idmuzakki   = $_POST['idmuzakki'];
    $jenisbayar  = $_POST['jenisbayar'];
    $jtyd        = $_POST['jtyd'];
    $bayarberas  = 2.5 ;
    $bayaruang   = 45000;
    $pembayaran = $_POST['pembayaran'];

    if ($jenisbayar == "beras" ) {
        $bayar_beras =$jtyd * $bayarberas;
        $tambah = mysqli_query($c, "insert INTO bayarzakat (id_muzakki,nama_KK,jumlah_tanggungan,jumlah_tanggunganyangdibayar,jenis_bayar,bayar_beras,Pembayaran) select '$idmuzakki',m.nama_muzakki, m.jumlah_tanggungan, '$jtyd','$jenisbayar','$bayar_beras','$pembayaran' from muzakki m left join bayarzakat b on m.id_muzakki=b.id_muzakki where m.id_muzakki = '$idmuzakki'");
    }

    else if ($jenisbayar == "uang" ) {
        $bayar_uang = $jtyd * $bayaruang;
        $tambah = mysqli_query($c, "insert INTO bayarzakat (id_muzakki,nama_KK,jumlah_tanggungan,jumlah_tanggunganyangdibayar,jenis_bayar,bayar_uang,Pembayaran) select '$idmuzakki',m.nama_muzakki, m.jumlah_tanggungan, '$jtyd','$jenisbayar','$bayar_uang','$pembayaran' from muzakki m left join bayarzakat b on m.id_muzakki=b.id_muzakki where m.id_muzakki = '$idmuzakki'");
    }


    if($tambah){
        header('location:bayarzakat.php');
    } else {
        echo '
        <script> alert ("Gagal membayar zakat");
        window.location.href="bayarzakat.php"
        </script>
        ';
    }

}

if(isset($_POST['editbayarzakat'])){
    $idmuzakki   = $_POST['idmuzakki'];
    $jenisbayar   = $_POST['jenisbayar'];
    $jtyd   = $_POST['jtyd'];
    $bayarberas  = 2.5 ;
    $bayaruang   = 45000;
    $pembayaran =$_POST['pembayaran'];


    if ($jenisbayar == "beras" ) {
        $bayar_beras =$jtyd * $bayarberas;
        $edit = mysqli_query($c, "update bayarzakat b left join muzakki m ON b.id_muzakki = m.id_muzakki set  nama_KK = m.nama_muzakki , b.jumlah_tanggungan = m.jumlah_tanggungan , jumlah_tanggunganyangdibayar = '$jtyd' , jenis_bayar = '$jenisbayar' , bayar_beras = '$bayar_beras' , bayar_uang = 0 , pembayaran = '$pembayaran' where b.id_muzakki = '$idmuzakki'");
    }

    else if ($jenisbayar == "uang" ) {
        $bayar_uang = $jtyd * $bayaruang;
        $edit = mysqli_query($c, "update bayarzakat b left join muzakki m ON b.id_muzakki = m.id_muzakki set  nama_KK = m.nama_muzakki , b.jumlah_tanggungan = m.jumlah_tanggungan , jumlah_tanggunganyangdibayar = '$jtyd' , jenis_bayar = '$jenisbayar' , bayar_uang = '$bayar_uang' , bayar_beras = 0 , pembayaran = '$pembayaran' where b.id_muzakki = '$idmuzakki'");
    }
    if($edit ){
        header('location:bayarzakat.php');
    } else {
        echo '
        <script> alert ("Gagal mengedit data ini");
        window.location.href="bayarzakat.php"
        </script>
        ';
    }

}
if(isset($_POST['hapusbayarzakat'])){
    $idzakat          = $_POST['idzakat'];

    $hapus = mysqli_query($c,"delete from bayarzakat where id_zakat = '$idzakat'");

    if($hapus ){
        header('location:bayarzakat.php');
    } else {
        echo '
        <script> alert ("Gagal menghapus data ini");
        window.location.href="bayarzakat.php"
        </script>
        ';
    }

}

if(isset($_POST['distribusimustahik'])){

    $nama   = $_POST['dnamamustahik'];
    $hak = $_POST['hak'];
    $kategori = $_POST['kategori'];
    $harga_beras = 2.5;
    $penerimaan = $hak * $harga_beras;
    $tambah = mysqli_query($c, "insert INTO mustahik_lainnya (nama,kategori,hak,penerimaan) values ('$nama', '$kategori', '$hak','$penerimaan')");
    if($tambah){
        header('location:dmustahik.php');
    } else {
        echo '
        <script> alert ("Gagal memasukan data");
        window.location.href="dmustahik.php"
        </script>
        ' ;
    }
}
if(isset($_POST['hapusmustahiklainnya'])){
    $idmustahiklainnya          = $_POST['idmustahiklainnya'];

    $hapus = mysqli_query($c,"delete from mustahik_lainnya where id_mustahiklainnya = '$idmustahiklainnya'");

    if($hapus ){
        header('location:dmustahik.php');
    } else {
        echo '
        <script> alert ("Gagal menghapus data ini");
        window.location.href="dmustahik.php"
        </script>
        ';
    }

}
if(isset($_POST['editmustahiklainnya'])){

    $idmustahiklainnya  = $_POST['idmustahiklainnya'];
    $nama   = $_POST['dnamamustahik'];
    $hak = $_POST['hak'];
    $kategori = $_POST['kategori'];
    $harga_beras = 2.5;
    $penerimaan = $hak * $harga_beras;
    $edit = mysqli_query($c,"update mustahik_lainnya set nama = '$nama', kategori = '$kategori', hak = '$hak', penerimaan = '$penerimaan'  where id_mustahiklainnya = '$idmustahiklainnya'");
    if($edit){
        header('location:dmustahik.php');
    } else {
        echo '
        <script> alert ("Gagal memasukan data");
        window.location.href="dmustahik.php"
        </script>
        ' ;
    }
}

if(isset($_POST['distribusiwarga'])){

    $idmuzakki   = $_POST['distribusiidmuzakki'];
    
    $penerimaan ="Sudah ";
    $keterangan = $_POST['keterangan'];
    $totalberas = $_POST['totalberas'];
    $totaluang = $_POST['totaluang'];
    $totalmampu = $_POST['totalmampu'];
    $totalfakir = $_POST['totalfakir'];
    $totalmiskin = $_POST['totalmiskin'];
    
    $hakfakir1 = (2.5 * 2).  " Kg";
    $hakfakir2 = "Rp " . number_format((45000 * 2), 0, ',', '.');
    $hakmiskin1 = (2.5 * 2) .  " Kg";
    $hakmiskin2 = "Rp " . number_format((45000 * 2), 0, ',', '.');
    $hakmampu1 = (2.5 * 1) .  " Kg";
    $hakmampu2 = "Rp " . number_format((45000 * 1), 0, ',', '.');

    if ($keterangan == "Mampu" ) {
        $tambah = mysqli_query($c, "insert INTO mustahik_warga (id_muzakki,nama,kategori,hak_beras,hak_uang,penerimaan) select '$idmuzakki',m.nama_muzakki, '$keterangan', '$hakmampu1','$hakmampu2','$penerimaan' from muzakki m left join mustahik_warga b on m.id_muzakki=b.id_muzakki where m.id_muzakki = '$idmuzakki'");
    }

    else if ($keterangan == "Fakir" ) {
        $tambah = mysqli_query($c, "insert INTO mustahik_warga (id_muzakki,nama,kategori,hak_beras,hak_uang,penerimaan) select '$idmuzakki',m.nama_muzakki, '$keterangan', '$hakfakir1','$hakfakir2', '$penerimaan' from muzakki m left join mustahik_warga b on m.id_muzakki=b.id_muzakki where m.id_muzakki = '$idmuzakki'");
    }
    else if ($keterangan == "Miskin" ) {
        $tambah = mysqli_query($c, "insert INTO mustahik_warga (id_muzakki,nama,kategori,hak_beras,hak_uang,penerimaan) select '$idmuzakki',m.nama_muzakki, '$keterangan', '$hakmiskin1','$hakmiskin2','$penerimaan' from muzakki m left join mustahik_warga b on m.id_muzakki=b.id_muzakki where m.id_muzakki = '$idmuzakki'");
    }


    if($tambah){
        header('location:dwarga.php');
    } else {
        echo '
        <script> alert ("Gagal memasukan data");
        window.location.href="dwarga.php"
        </script>
        ' ;
    }
}

if(isset($_POST['hapusmustahikwarga'])){
    $idmustahikwarga          = $_POST['idmustahikwarga'];

    $hapus = mysqli_query($c,"delete from mustahik_warga where id_mustahikwarga = '$idmustahikwarga'");

    if($hapus ){
        header('location:dwarga.php');
    } else {
        echo '
        <script> alert ("Gagal menghapus data ini");
        window.location.href="dwarga.php"
        </script>
        ';
    }

}
if(isset($_POST['editdistribusiwarga'])){
    $idmuzakki   = $_POST['distribusiidmuzakki'];
    $keterangan   = $_POST['keterangan'];
    $hakfakir = (2.5 * 2).  " Kg";
    $hakmiskin = (2.5 * 2).  " Kg";
    $hakmampu = (2.5 * 1).  " Kg";
    $penerimaan = $_POST['penerimaan'];


    if ($keterangan == "Fakir" ) {
        $edit = mysqli_query($c, "update mustahik_warga b left join muzakki m ON b.id_muzakki = m.id_muzakki set  nama = m.nama_muzakki , kategori = '$keterangan' , m.keterangan = '$keterangan' , hak_beras = '$hakfakir', penerimaan ='$penerimaan' where b.id_muzakki = '$idmuzakki'");
    }

    if ($keterangan == "Miskin" ) {
        $edit = mysqli_query($c, "update mustahik_warga b left join muzakki m ON b.id_muzakki = m.id_muzakki set  nama = m.nama_muzakki , kategori = '$keterangan' , m.keterangan = '$keterangan' , hak_beras = '$hakmiskin', penerimaan ='$penerimaan' where b.id_muzakki  = '$idmuzakki'");
    }

    if ($keterangan == "Mampu" ) {
        $edit = mysqli_query($c, "update mustahik_warga b left join muzakki m ON b.id_muzakki = m.id_muzakki set  nama = m.nama_muzakki , kategori = '$keterangan' , m.keterangan = '$keterangan' , hak_beras = '$hakmampu', penerimaan ='$penerimaan' where b.id_muzakki = '$idmuzakki'");
    }

    if($edit ){
        header('location:dwarga.php');
    } else {
        echo '
        <script> alert ("Gagal mengedit data ini");
        window.location.href="dwarga.php"
        </script>
        ';
    }

}
?>