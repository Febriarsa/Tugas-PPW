<!DOCTYPE html>
 <html>
 <head>
    <title>Praktikum Pemrograman Web</title>
 </head>
 <body>
   <h3>Halo, {{ $data['name'] }}!</h3>
   <p>Terima kasih telah mendaftar di aplikasi kami. Berikut adalah detail akun Anda:</p>
   <ul>
       <li>Nama: {{ $data['name'] }}</li>
       <li>Email: {{ $data['email'] }}</li>
       <li>Nomor Telepon: {{ $data['number'] }}</li>
       <li>Adress: {{ $data['address'] }}</li>
   </ul>
   <p>Selamat menggunakan layanan kami!</p>
   
    <p>Terima kasih</p>
 </body>
 </html>