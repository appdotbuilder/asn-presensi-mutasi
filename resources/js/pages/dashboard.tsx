import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Opd {
    id: number;
    name: string;
    code: string;
    description?: string;
}

interface User {
    id: number;
    name: string;
    nip?: string;
    profile?: {
        full_name: string;
    };
    opd?: Opd;
}

interface Attendance {
    id: number;
    date: string;
    status: string;
    approval_status: string;
    user?: User;
    approver?: User;
}

interface Mutation {
    id: number;
    type: string;
    status: string;
    from_opd?: Opd;
    to_opd?: Opd;
    user?: User;
}

interface OpdStats extends Opd {
    asn_count?: number;
}

interface Props {
    userRole?: string;
    stats?: {
        todayAttendance?: Attendance;
        monthlyAttendanceCount?: number;
        monthlyPresentCount?: number;
        pendingMutations?: number;
        opdAsnUsers?: number;
        pendingAttendances?: number;
        todayAttendanceStats?: Record<string, number>;
        totalAsn?: number;
        totalOpds?: number;
        totalOperators?: number;
    };
    opd?: Opd;
    recentAttendances?: Attendance[];
    recentMutations?: Mutation[];
    opdStats?: OpdStats[];
    error?: string;
    [key: string]: unknown;
}

export default function Dashboard({ 
    userRole, 
    stats = {}, 
    opd, 
    recentAttendances = [], 
    recentMutations = [], 
    opdStats = [],
    error 
}: Props) {
    if (error) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                    <div className="rounded-lg bg-red-50 border border-red-200 p-4">
                        <div className="flex items-center gap-3">
                            <span className="text-2xl">⚠️</span>
                            <div>
                                <h3 className="font-semibold text-red-800">Akses Terbatas</h3>
                                <p className="text-red-700">{error}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </AppLayout>
        );
    }

    const renderAsnDashboard = () => (
        <>
            {/* ASN Stats Cards */}
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-green-100 rounded-lg">
                            <span className="text-xl">📅</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Kehadiran Hari Ini</p>
                            <p className="text-2xl font-bold text-gray-900">
                                {stats.todayAttendance ? '✅' : '⏳'}
                            </p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">
                        {stats.todayAttendance 
                            ? `Status: ${stats.todayAttendance.status}` 
                            : 'Belum presensi'
                        }
                    </p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-blue-100 rounded-lg">
                            <span className="text-xl">📊</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Kehadiran Bulan Ini</p>
                            <p className="text-2xl font-bold text-gray-900">
                                {stats.monthlyPresentCount}/{stats.monthlyAttendanceCount}
                            </p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Hari kerja</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-yellow-100 rounded-lg">
                            <span className="text-xl">📝</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Mutasi Pending</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.pendingMutations || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Pengajuan aktif</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-purple-100 rounded-lg">
                            <span className="text-xl">⏰</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Quick Action</p>
                            <Link 
                                href="/attendances/create"
                                className="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700"
                            >
                                <span>➕</span> Presensi
                            </Link>
                        </div>
                    </div>
                    <Link 
                        href="/mutations/create"
                        className="inline-flex items-center gap-1 text-sm font-medium text-green-600 hover:text-green-700"
                    >
                        <span>📄</span> Ajukan Mutasi
                    </Link>
                </div>
            </div>

            {/* Recent Activities */}
            <div className="grid gap-6 lg:grid-cols-2">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <h3 className="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span>📋</span> Presensi Terbaru
                    </h3>
                    {recentAttendances.length > 0 ? (
                        <div className="space-y-3">
                            {recentAttendances.map((attendance: Attendance) => (
                                <div key={attendance.id} className="flex justify-between items-center py-2 border-b last:border-b-0">
                                    <div>
                                        <p className="font-medium">{attendance.date}</p>
                                        <p className="text-sm text-gray-600">
                                            Status: <span className="capitalize font-medium">{attendance.status}</span>
                                        </p>
                                    </div>
                                    <div className="text-right">
                                        <span className={`inline-flex px-2 py-1 text-xs rounded-full ${
                                            attendance.approval_status === 'approved' 
                                                ? 'bg-green-100 text-green-800' 
                                                : attendance.approval_status === 'rejected'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-yellow-100 text-yellow-800'
                                        }`}>
                                            {attendance.approval_status === 'approved' ? '✅ Disetujui' :
                                             attendance.approval_status === 'rejected' ? '❌ Ditolak' : '⏳ Pending'}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Belum ada data presensi</p>
                    )}
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <h3 className="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span>🔄</span> Status Mutasi
                    </h3>
                    {recentMutations.length > 0 ? (
                        <div className="space-y-3">
                            {recentMutations.map((mutation: Mutation) => (
                                <div key={mutation.id} className="p-3 border border-gray-200 rounded-lg">
                                    <div className="flex justify-between items-start mb-2">
                                        <p className="font-medium text-sm">{mutation.type}</p>
                                        <span className={`inline-flex px-2 py-1 text-xs rounded-full ${
                                            mutation.status.includes('approved') ? 'bg-green-100 text-green-800' :
                                            mutation.status.includes('rejected') ? 'bg-red-100 text-red-800' :
                                            'bg-blue-100 text-blue-800'
                                        }`}>
                                            {mutation.status.replace('_', ' ')}
                                        </span>
                                    </div>
                                    <p className="text-xs text-gray-600">
                                        {mutation.from_opd?.name} → {mutation.to_opd?.name}
                                    </p>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Belum ada pengajuan mutasi</p>
                    )}
                </div>
            </div>
        </>
    );

    const renderOperatorDashboard = () => (
        <>
            {/* OPD Info */}
            <div className="rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 mb-6">
                <h2 className="text-xl font-bold mb-2 flex items-center gap-2">
                    <span>🏢</span> {opd?.name || 'OPD Tidak Tersedia'}
                </h2>
                <p className="text-blue-100">{opd?.description}</p>
            </div>

            {/* Stats Cards */}
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-blue-100 rounded-lg">
                            <span className="text-xl">👥</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Total ASN</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.opdAsnUsers || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">ASN di OPD</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-yellow-100 rounded-lg">
                            <span className="text-xl">⏳</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Presensi Pending</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.pendingAttendances || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Menunggu persetujuan</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-purple-100 rounded-lg">
                            <span className="text-xl">🔄</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Mutasi Review</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.pendingMutations || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Untuk ditinjau</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-green-100 rounded-lg">
                            <span className="text-xl">📊</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Hadir Hari Ini</p>
                            <p className="text-2xl font-bold text-gray-900">
                                {stats.todayAttendanceStats?.present || 0}
                            </p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">ASN hadir</p>
                </div>
            </div>

            {/* Recent Activities */}
            <div className="grid gap-6 lg:grid-cols-2">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-lg font-semibold flex items-center gap-2">
                            <span>📋</span> Presensi Pending
                        </h3>
                        <Link 
                            href="/attendances"
                            className="text-sm text-blue-600 hover:text-blue-700 font-medium"
                        >
                            Lihat Semua →
                        </Link>
                    </div>
                    {recentAttendances.length > 0 ? (
                        <div className="space-y-3">
                            {recentAttendances.slice(0, 5).map((attendance: Attendance) => (
                                <div key={attendance.id} className="flex justify-between items-center py-2 border-b last:border-b-0">
                                    <div>
                                        <p className="font-medium">{attendance.user?.name}</p>
                                        <p className="text-sm text-gray-600">{attendance.date} - {attendance.status}</p>
                                    </div>
                                    <div>
                                        <Link
                                            href={`/attendances/${attendance.id}`}
                                            className="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full hover:bg-blue-200"
                                        >
                                            👁️ Review
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Tidak ada presensi pending</p>
                    )}
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-lg font-semibold flex items-center gap-2">
                            <span>🔄</span> Mutasi Review
                        </h3>
                        <Link 
                            href="/mutations"
                            className="text-sm text-blue-600 hover:text-blue-700 font-medium"
                        >
                            Lihat Semua →
                        </Link>
                    </div>
                    {recentMutations.length > 0 ? (
                        <div className="space-y-3">
                            {recentMutations.map((mutation: Mutation) => (
                                <div key={mutation.id} className="p-3 border border-gray-200 rounded-lg">
                                    <div className="flex justify-between items-start mb-2">
                                        <div>
                                            <p className="font-medium text-sm">{mutation.user?.name}</p>
                                            <p className="text-xs text-gray-600 capitalize">{mutation.type}</p>
                                        </div>
                                        <Link
                                            href={`/mutations/${mutation.id}`}
                                            className="inline-flex items-center gap-1 text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full hover:bg-purple-200"
                                        >
                                            📝 Review
                                        </Link>
                                    </div>
                                    <p className="text-xs text-gray-600">
                                        Ke: {mutation.to_opd?.name}
                                    </p>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Tidak ada mutasi untuk review</p>
                    )}
                </div>
            </div>
        </>
    );

    const renderAdminDashboard = () => (
        <>
            {/* Admin Stats Cards */}
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-blue-100 rounded-lg">
                            <span className="text-xl">👥</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Total ASN</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.totalAsn || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Seluruh sistem</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-green-100 rounded-lg">
                            <span className="text-xl">🏢</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Total OPD</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.totalOpds || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Aktif</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-purple-100 rounded-lg">
                            <span className="text-xl">👔</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Operator OPD</p>
                            <p className="text-2xl font-bold text-gray-900">{stats.totalOperators || 0}</p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Total operator</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="p-2 bg-yellow-100 rounded-lg">
                            <span className="text-xl">⏳</span>
                        </div>
                        <div>
                            <p className="text-sm text-gray-600">Pending Review</p>
                            <p className="text-2xl font-bold text-gray-900">
                                {(stats.pendingMutations || 0) + (stats.pendingAttendances || 0)}
                            </p>
                        </div>
                    </div>
                    <p className="text-sm text-gray-500">Butuh perhatian</p>
                </div>
            </div>

            {/* System Overview */}
            <div className="grid gap-6 lg:grid-cols-2">
                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <h3 className="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span>📊</span> Statistik OPD
                    </h3>
                    {opdStats.length > 0 ? (
                        <div className="space-y-3">
                            {opdStats.slice(0, 8).map((opd: OpdStats) => (
                                <div key={opd.id} className="flex justify-between items-center py-2 border-b last:border-b-0">
                                    <div>
                                        <p className="font-medium text-sm">{opd.name}</p>
                                        <p className="text-xs text-gray-600">{opd.code}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-semibold text-blue-600">{opd.asn_count || 0}</p>
                                        <p className="text-xs text-gray-500">ASN</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Belum ada data OPD</p>
                    )}
                </div>

                <div className="rounded-lg bg-white p-6 shadow-sm border">
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-lg font-semibold flex items-center gap-2">
                            <span>🔄</span> Mutasi BKPSDM Review
                        </h3>
                        <Link 
                            href="/mutations"
                            className="text-sm text-blue-600 hover:text-blue-700 font-medium"
                        >
                            Lihat Semua →
                        </Link>
                    </div>
                    {recentMutations.length > 0 ? (
                        <div className="space-y-3">
                            {recentMutations.slice(0, 6).map((mutation: Mutation) => (
                                <div key={mutation.id} className="p-3 border border-gray-200 rounded-lg">
                                    <div className="flex justify-between items-start mb-2">
                                        <div>
                                            <p className="font-medium text-sm">{mutation.user?.name}</p>
                                            <p className="text-xs text-gray-600 capitalize">{mutation.type}</p>
                                        </div>
                                        <Link
                                            href={`/mutations/${mutation.id}`}
                                            className="inline-flex items-center gap-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full hover:bg-red-200"
                                        >
                                            ⚡ Final Review
                                        </Link>
                                    </div>
                                    <p className="text-xs text-gray-600">
                                        {mutation.from_opd?.name} → {mutation.to_opd?.name}
                                    </p>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500 text-center py-4">Tidak ada mutasi untuk review</p>
                    )}
                </div>
            </div>
        </>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                {/* Welcome Header */}
                <div className="rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-6">
                    <h1 className="text-2xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <span>👋</span> 
                        Selamat Datang, {
                            userRole === 'asn' ? 'ASN' :
                            userRole === 'operator_opd' ? 'Operator OPD' :
                            userRole === 'admin' ? 'Admin BKPSDM' : 'User'
                        }!
                    </h1>
                    <p className="text-gray-600">
                        {userRole === 'asn' && 'Kelola presensi dan pengajuan mutasi Anda dengan mudah'}
                        {userRole === 'operator_opd' && 'Pantau dan kelola ASN di OPD Anda'}
                        {userRole === 'admin' && 'Kelola seluruh sistem manajemen ASN'}
                    </p>
                </div>

                {/* Role-based Dashboard Content */}
                {userRole === 'asn' && renderAsnDashboard()}
                {userRole === 'operator_opd' && renderOperatorDashboard()}
                {userRole === 'admin' && renderAdminDashboard()}
            </div>
        </AppLayout>
    );
}