# Dokumentasi Perbaikan Pada Backend TokoHebat

## Latar Belakang

Backend TokoHebat sebelumnya dibuat dengan implementasi keamanan yang buruk (“Kode Yoga”). Walaupun aplikasi terlihat berjalan normal, terdapat beberapa celah serius yang dapat menyebabkan:

- pengambilalihan akun pengguna,
- akses ilegal ke halaman admin,
- hingga kebocoran seluruh password pelanggan.

Pada proses audit kode, ditemukan tiga masalah utama:

1. Authentication Bypass
2. Broken Authorization
3. Plain Text Password Storage

Dokumentasi ini menjelaskan:
- penyebab masalah,
- dampak keamanan,
- contoh implementasi yang salah,
- serta solusi perbaikannya menggunakan fitur bawaan Laravel.

---

# 1. Authentication Bypass

## Deskripsi Masalah

Sistem login hanya memeriksa email pengguna tanpa melakukan verifikasi password.

Akibatnya, siapa pun dapat masuk ke akun pengguna lain hanya dengan mengetahui email mereka.

---

## Kode Yoga (Salah)

```php
public function login(array $data)
{
    return User::where('email', $data['email'])
        ->first();
}
```

---

## Kenapa Ini Berbahaya?

Kode di atas:
- tidak memverifikasi password,
- tidak melakukan hashing verification,
- langsung menganggap email valid sebagai login berhasil.

Artinya:

```text
Penyerang hanya perlu mengetahui email korban
untuk mengambil alih akun mereka.
```

Ini termasuk:

> Authentication Bypass Vulnerability

---

## Dampak Jika Dibiarkan

- Account takeover
- Penyalahgunaan akun pengguna
- Kebocoran data pribadi
- Penyalahgunaan transaksi
- Kerusakan reputasi aplikasi

---

## Solusi Perbaikan

Laravel sudah menyediakan sistem authentication aman melalui:

```php
Auth::attempt()
```

Method ini otomatis:
- memverifikasi password,
- mencocokkan hash password,
- membuat sesi/token login dengan benar.

---

## Kode Perbaikan

```php
public function login(array $data)
{
    if (!Auth::attempt([
        'email' => $data['email'],
        'password' => $data['password']
    ])) {
        return false;
    }

    return Auth::user();
}
```

---

## Hasil Setelah Perbaikan

Sistem sekarang:
- mewajibkan email dan password valid,
- menolak login ilegal,
- mengikuti standar authentication Laravel.

---

# 2. Broken Authorization

## Deskripsi Masalah

Endpoint admin dapat diakses oleh user biasa karena tidak memiliki pembatasan role.

---

## Kode Yoga (Salah)

```php
oute::apiResource('categories', CategoryController::class)->except(['index', 'show']);
```

Tidak terdapat:
- middleware auth,
- middleware admin,
- validasi role pengguna.

---

## Kenapa Ini Berbahaya?

User biasa dapat:
- mengakses data admin,
- mengubah produk,
- menghapus data,
- melakukan privilege escalation.

Bahkan dalam beberapa kasus:
- hanya dengan mengganti ID atau URL.

Contoh:

```text
/admin/products
/admin/users
/admin/orders
```

---

## Dampak Jika Dibiarkan

- Kebocoran data internal
- Manipulasi database
- Penghapusan data penting
- Pengambilalihan sistem admin

Ini termasuk:

> Broken Access Control / Broken Authorization

Dan merupakan salah satu celah keamanan paling berbahaya menurut OWASP.

---

## Solusi Perbaikan

Menggunakan:
- `auth:sanctum`
- middleware role admin

---

## Middleware Admin

```php
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}
```

---

## Penggunaan Middleware

```php
Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
});
```

---

## Hasil Setelah Perbaikan

Sekarang:
- hanya admin yang dapat mengakses endpoint admin,
- user biasa otomatis ditolak,
- authorization menjadi lebih aman dan terstruktur.

---

# 3. Plain Text Password Storage

## Deskripsi Masalah

Password pengguna disimpan langsung ke database tanpa enkripsi atau hashing.

---

## Kode Yoga (Salah)

```php
User::create([
    'name' => $data['name'],
    'email' => $data['email'],
    'password' => $data['password']
]);
```

---

## Kenapa Ini Berbahaya?

Jika database bocor:
- semua password pengguna langsung terlihat,
- penyerang dapat mencoba password tersebut di layanan lain.

Karena banyak pengguna memakai password yang sama:
- Gmail
- media sosial
- e-wallet
- internet banking

maka dampaknya bisa sangat besar.

---

## Dampak Jika Dibiarkan

- Kebocoran seluruh akun pengguna
- Credential stuffing attack
- Penyalahgunaan akun eksternal
- Kehilangan kepercayaan pengguna

Ini termasuk:

> Sensitive Data Exposure

---

## Solusi Perbaikan

Laravel menyediakan hashing bawaan menggunakan:

```php
Hash::make()
```

---

## Kode Perbaikan

```php
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => $data['name'],
    'email' => $data['email'],
    'password' => Hash::make($data['password'])
]);
```

---

## Hasil Setelah Perbaikan

Password sekarang:
- tersimpan dalam bentuk hash,
- tidak dapat dibaca langsung,
- lebih aman jika database bocor.

Contoh hasil hash:

```text
$2y$12$YpM9F0....
```

---

# Kesimpulan

Masalah utama pada “Kode Yoga” bukan pada syntax atau error program, tetapi pada lemahnya implementasi security backend.

Tiga masalah utama yang ditemukan:

| Masalah | Risiko |
|---|---|
| Authentication Bypass | Pengambilalihan akun |
| Broken Authorization | Akses ilegal admin |
| Plain Text Password | Kebocoran seluruh password |

Laravel sebenarnya sudah menyediakan fitur keamanan bawaan yang cukup kuat, seperti:
- `Auth::attempt()`
- `Hash::make()`
- Middleware
- Sanctum Authentication

Dengan menggunakan fitur tersebut secara benar, sistem menjadi:
- lebih aman,
- lebih terstruktur,
- dan lebih sesuai dengan standar backend modern.



