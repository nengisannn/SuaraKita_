# Dokumentasi API SuaraKita AI

Endpoint ini menyediakan jawaban asisten SuaraKita tentang pengaduan kampus dan fitur platform. Implementasinya berada di [`chat_ai.php`](./chat_ai.php).

## Endpoint

```text
POST /api/chat_ai.php
Content-Type: application/json
```

Endpoint dipanggil oleh widget AI untuk mahasiswa, admin, dan halaman utama.

## Request

Kirim objek JSON dengan properti `message` berupa teks pertanyaan:

```json
{
  "message": "Bagaimana cara membuat pengaduan?"
}
```

Pesan kosong atau hanya berisi spasi akan menghasilkan respons error.

## Response

Jika pesan kosong:

```json
{
  "status": "error",
  "reply": "Mohon maaf, pesan Anda tidak boleh kosong."
}
```

Jika pertanyaan diproses:

```json
{
  "status": "success",
  "reply": "..."
}
```

Nilai `reply` berupa teks jawaban; beberapa jawaban fallback menggunakan markup HTML seperti `<strong>` dan `<br/>`.

## Konfigurasi penyedia AI

Endpoint membaca API key dari environment variable server. Atur satu atau lebih variabel berikut:

| Environment variable | Penyedia | Model yang digunakan |
| --- | --- | --- |
| `GEMINI_API_KEY` | Google Gemini | `gemini-1.5-flash` |
| `OPENAI_API_KEY` | OpenAI | `gpt-3.5-turbo` |
| `DEEPSEEK_API_KEY` | DeepSeek | Belum terhubung ke pemanggilan API di implementasi saat ini |

Jangan menaruh API key di kode frontend atau mengunggahnya ke repositori. Simpan sebagai environment variable pada server/deployment.

### Urutan pemrosesan

1. Jika `GEMINI_API_KEY` tersedia, endpoint mencoba Gemini terlebih dahulu.
2. Jika Gemini tidak memberikan jawaban yang dapat digunakan, endpoint mencoba OpenAI jika `OPENAI_API_KEY` tersedia.
3. Jika kedua penyedia tidak menjawab, endpoint menggunakan mesin respons fallback lokal berbasis pola kata kunci.

Pemanggilan ke Gemini dan OpenAI masing-masing memiliki batas waktu 10 detik. `DEEPSEEK_API_KEY` saat ini dibaca oleh kode, tetapi tidak digunakan untuk mengirim permintaan ke DeepSeek.

## Batasan jawaban

Instruksi untuk model membatasi asisten ke topik SuaraKita, seperti:

- Cara membuat dan melacak pengaduan.
- Mode anonim dan privasi.
- Status urgensi dan waktu respons.
- Kategori pengaduan serta ketentuan lampiran.

Mesin fallback mengenali beberapa topik tersebut menggunakan pola kata kunci dan menolak pertanyaan di luar topik. Balasan fallback dibuat dalam Bahasa Indonesia.

## Contoh pemanggilan

```javascript
const response = await fetch('/api/chat_ai.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    message: 'Format lampiran apa saja yang didukung?'
  })
});

const data = await response.json();
if (data.status === 'success') {
  console.log(data.reply);
} else {
  console.error(data.reply);
}
```
