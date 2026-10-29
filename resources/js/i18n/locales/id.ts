import type en from './en';

export default {
    dashboard: 'Dasbor', workspace: 'Ruang kerja', personal: 'Pribadi', navigation: 'Navigasi',
    openNavigation: 'Buka navigasi', closeNavigation: 'Tutup navigasi', collapse: 'Ciutkan bilah sisi', expand: 'Perluas bilah sisi',
    skip: 'Langsung ke konten', language: 'Bahasa', apply: 'Terapkan', saving: 'Menyimpan…', failure: 'Permintaan gagal diselesaikan. Silakan coba lagi.',
    switchWorkspace: 'Ganti ruang kerja', switching: 'Mengganti…', current: 'Ruang kerja aktif', signOut: 'Keluar',
    account: 'Akun Anda', welcome: 'Selamat datang, {name}', overview: 'Ruang kerja konferensi Anda',
    introduction: 'Satu akun, dengan ruang kerja untuk setiap peran yang diberikan kepada Anda.',
    ready: 'Sudah masuk', preparation: 'Layanan konferensi sedang disiapkan',
    preparationBody: 'Pengiriman makalah, peninjauan, keuangan, dan pengelolaan situs belum tersedia di sini. Tindakan akun yang tersedia ditampilkan pada navigasi.',
    context: 'Konteks ruang kerja', contextHelp: 'Informasi ini berasal dari penugasan aktif di server. Pergantian ruang kerja tetap menggunakan akun yang sama.',
    personalHelp: 'Ruang pribadi Anda selalu tersedia. Ruang kerja tambahan muncul saat Anda mendapat peran aktif.',
    event: 'Acara', institution: 'Institusi', role: 'Peran', identity: 'Identitas akun',
    roles: { participant: 'Peserta', presenter: 'Pemakalah', reviewer: 'Peninjau', cohost_pic: 'Perwakilan Co-Host', superadmin: 'Superadmin' },
    auth: {
        signIn: 'Masuk', register: 'Masuk atau daftar', description: 'Terima kode dan tautan masuk melalui email. Tanpa kata sandi.',
        name: 'Nama (opsional untuk akun baru)', email: 'Email', send: 'Kirim / kirim ulang kode', haveCode: 'Sudah punya kode?',
        receivingEmail: 'Email penerima kode', code: 'Kode enam digit', verify: 'Verifikasi dan masuk',
        sent: 'Jika diizinkan, periksa email Anda untuk kode atau tautan masuk.',
        confirm: 'Konfirmasi masuk', confirmHelp: 'Lanjutkan untuk memverifikasi email dan masuk ke ICHES.', confirmAction: 'Konfirmasi dan masuk',
        newCode: 'Minta kode atau tautan baru', demo: 'Masuk demo lokal', demoHelp: 'Gunakan akun demo sintetis yang disediakan untuk pratinjau lokal ini.',
        demoEmail: 'Email demo', password: 'Kata sandi', invalid: 'Periksa nilai ini dan coba lagi.',
        invalidCode: 'Kode tidak valid atau kedaluwarsa. Minta kode baru.', invalidLink: 'Tautan tidak valid atau kedaluwarsa. Minta tautan baru.',
        invalidCredentials: 'Tidak dapat masuk dengan informasi ini.', throttled: 'Terlalu banyak percobaan. Tunggu dan coba lagi.',
    },
} satisfies typeof en;
