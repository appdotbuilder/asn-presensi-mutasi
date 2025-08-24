import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="ASN Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 p-6 text-gray-800 lg:justify-center lg:p-8">
                <header className="mb-8 w-full max-w-7xl text-sm">
                    <nav className="flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-lg">
                                🏛️
                            </div>
                            <span className="font-semibold text-xl text-gray-700">ASN Manager</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                                >
                                    <span>📊</span>
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-block rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                                    >
                                        Masuk
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-block rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                                    >
                                        Daftar
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <div className="w-full max-w-7xl">
                    <main className="text-center">
                        {/* Hero Section */}
                        <div className="mb-16">
                            <div className="mb-6 flex justify-center">
                                <div className="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-4xl shadow-xl">
                                    🏛️
                                </div>
                            </div>
                            <h1 className="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">
                                🏛️ Sistem Manajemen ASN
                            </h1>
                            <p className="mb-8 text-xl text-gray-600 lg:text-2xl max-w-3xl mx-auto">
                                Platform komprehensif untuk pengelolaan data Aparatur Sipil Negara, 
                                presensi kehadiran, dan pengajuan mutasi dengan sistem persetujuan bertingkat
                            </p>
                            
                            {!auth.user && (
                                <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center gap-3 rounded-xl bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-blue-700 hover:scale-105 shadow-lg"
                                    >
                                        <span>🚀</span>
                                        Mulai Sekarang
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center gap-3 rounded-xl border-2 border-blue-600 px-8 py-4 text-lg font-semibold text-blue-600 transition-all hover:bg-blue-50"
                                    >
                                        <span>🔑</span>
                                        Masuk ke Akun
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Features Grid */}
                        <div className="mb-16">
                            <h2 className="mb-12 text-3xl font-bold text-gray-900">
                                ✨ Fitur Utama Sistem
                            </h2>
                            <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                                {/* ASN Features */}
                                <div className="rounded-2xl bg-white p-8 shadow-lg border border-gray-100">
                                    <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-green-100 text-3xl mx-auto">
                                        👤
                                    </div>
                                    <h3 className="mb-3 text-xl font-semibold text-gray-900">Portal ASN</h3>
                                    <ul className="space-y-2 text-gray-600 text-left">
                                        <li className="flex items-center gap-2">
                                            <span className="text-green-500">✓</span>
                                            Kelola profil dan data pribadi
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-green-500">✓</span>
                                            Catat kehadiran harian
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-green-500">✓</span>
                                            Ajukan permohonan mutasi
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-green-500">✓</span>
                                            Pantau status pengajuan
                                        </li>
                                    </ul>
                                </div>

                                {/* OPD Operator Features */}
                                <div className="rounded-2xl bg-white p-8 shadow-lg border border-gray-100">
                                    <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-100 text-3xl mx-auto">
                                        🏢
                                    </div>
                                    <h3 className="mb-3 text-xl font-semibold text-gray-900">Operator OPD</h3>
                                    <ul className="space-y-2 text-gray-600 text-left">
                                        <li className="flex items-center gap-2">
                                            <span className="text-blue-500">✓</span>
                                            Kelola data ASN di OPD
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-blue-500">✓</span>
                                            Verifikasi kehadiran ASN
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-blue-500">✓</span>
                                            Review pengajuan mutasi
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-blue-500">✓</span>
                                            Laporan kehadiran bulanan
                                        </li>
                                    </ul>
                                </div>

                                {/* Admin Features */}
                                <div className="rounded-2xl bg-white p-8 shadow-lg border border-gray-100">
                                    <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-purple-100 text-3xl mx-auto">
                                        ⚙️
                                    </div>
                                    <h3 className="mb-3 text-xl font-semibold text-gray-900">Admin BKPSDM</h3>
                                    <ul className="space-y-2 text-gray-600 text-left">
                                        <li className="flex items-center gap-2">
                                            <span className="text-purple-500">✓</span>
                                            Dashboard analitik lengkap
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-purple-500">✓</span>
                                            Kelola seluruh data ASN
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-purple-500">✓</span>
                                            Finalisasi mutasi ASN
                                        </li>
                                        <li className="flex items-center gap-2">
                                            <span className="text-purple-500">✓</span>
                                            Laporan lintas OPD
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {/* Process Flow */}
                        <div className="mb-16">
                            <h2 className="mb-12 text-3xl font-bold text-gray-900">
                                🔄 Alur Kerja Sistem
                            </h2>
                            <div className="flex flex-col lg:flex-row gap-8 items-center justify-center">
                                <div className="flex-1 max-w-sm">
                                    <div className="rounded-2xl bg-gradient-to-br from-green-50 to-green-100 p-6 text-center border-2 border-green-200">
                                        <div className="mb-4 text-4xl">📝</div>
                                        <h3 className="mb-2 text-lg font-semibold text-green-800">1. Pengajuan ASN</h3>
                                        <p className="text-green-700">ASN mengajukan mutasi atau mencatat kehadiran melalui portal</p>
                                    </div>
                                </div>
                                
                                <div className="text-2xl text-gray-400">→</div>
                                
                                <div className="flex-1 max-w-sm">
                                    <div className="rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 p-6 text-center border-2 border-blue-200">
                                        <div className="mb-4 text-4xl">👔</div>
                                        <h3 className="mb-2 text-lg font-semibold text-blue-800">2. Review OPD</h3>
                                        <p className="text-blue-700">Operator OPD melakukan verifikasi dan persetujuan tingkat pertama</p>
                                    </div>
                                </div>
                                
                                <div className="text-2xl text-gray-400">→</div>
                                
                                <div className="flex-1 max-w-sm">
                                    <div className="rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 p-6 text-center border-2 border-purple-200">
                                        <div className="mb-4 text-4xl">✅</div>
                                        <h3 className="mb-2 text-lg font-semibold text-purple-800">3. Final BKPSDM</h3>
                                        <p className="text-purple-700">Admin BKPSDM memberikan persetujuan final dan penerbitan SK</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Stats Section */}
                        <div className="mb-16">
                            <div className="rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 p-12 text-white">
                                <h2 className="mb-8 text-3xl font-bold">📊 Efisiensi Administrasi ASN</h2>
                                <div className="grid gap-8 md:grid-cols-3">
                                    <div className="text-center">
                                        <div className="mb-2 text-4xl font-bold">95%</div>
                                        <div className="text-blue-100">Pengurangan Waktu Proses</div>
                                    </div>
                                    <div className="text-center">
                                        <div className="mb-2 text-4xl font-bold">100%</div>
                                        <div className="text-blue-100">Transparansi Data</div>
                                    </div>
                                    <div className="text-center">
                                        <div className="mb-2 text-4xl font-bold">24/7</div>
                                        <div className="text-blue-100">Akses Sistem</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* CTA Section */}
                        {!auth.user && (
                            <div className="rounded-3xl bg-gray-50 p-12 border border-gray-200">
                                <h2 className="mb-4 text-3xl font-bold text-gray-900">
                                    🚀 Siap Memulai?
                                </h2>
                                <p className="mb-8 text-xl text-gray-600">
                                    Bergabunglah dengan sistem manajemen ASN yang modern dan efisien
                                </p>
                                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center gap-3 rounded-xl bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-blue-700 hover:scale-105"
                                    >
                                        <span>📋</span>
                                        Daftar Sebagai ASN
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center gap-3 rounded-xl bg-gray-800 px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-gray-900"
                                    >
                                        <span>🏢</span>
                                        Login Operator/Admin
                                    </Link>
                                </div>
                            </div>
                        )}
                    </main>
                </div>

                <footer className="mt-16 w-full max-w-7xl border-t border-gray-200 pt-8 text-center text-sm text-gray-500">
                    <p>Sistem Manajemen ASN - Membangun Administrasi yang Efisien dan Transparan</p>
                    <p className="mt-2">
                        Dikembangkan dengan ❤️ untuk kemajuan pelayanan publik
                    </p>
                </footer>
            </div>
        </>
    );
}