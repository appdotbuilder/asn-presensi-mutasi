import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Presensi',
        href: '/attendances',
    },
];

interface Attendance {
    id: number;
    user_id: number;
    date: string;
    check_in: string | null;
    check_out: string | null;
    status: string;
    notes: string | null;
    approval_status: string;
    approved_by: number | null;
    approved_at: string | null;
    approval_notes: string | null;
    user?: {
        id: number;
        name: string;
        nip: string;
        opd?: {
            name: string;
        };
        profile?: {
            full_name: string;
        };
    };
    approver?: {
        name: string;
    };
}

interface Opd {
    id: number;
    name: string;
    code: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedData<T> {
    data: T[];
    links: PaginationLink[];
    meta: {
        from: number;
        to: number;
        total: number;
    };
}

interface Props {
    attendances: PaginatedData<Attendance>;
    currentMonth: string;
    userRole: string;
    opd?: Opd;
    filters?: {
        status?: string;
        approval?: string;
        opd?: string;
    };
    opds?: Opd[];
    [key: string]: unknown;
}

export default function AttendanceIndex({ 
    attendances, 
    currentMonth, 
    userRole, 
    opd,
    filters = {},
    opds = []
}: Props) {
    const getStatusBadge = (status: string) => {
        const statusMap = {
            present: { label: '✅ Hadir', class: 'bg-green-100 text-green-800' },
            absent: { label: '❌ Tidak Hadir', class: 'bg-red-100 text-red-800' },
            late: { label: '⏰ Terlambat', class: 'bg-orange-100 text-orange-800' },
            sick: { label: '🤒 Sakit', class: 'bg-blue-100 text-blue-800' },
            leave: { label: '🏖️ Cuti', class: 'bg-purple-100 text-purple-800' },
            business_trip: { label: '🚗 Dinas', class: 'bg-indigo-100 text-indigo-800' },
        };
        const statusInfo = statusMap[status as keyof typeof statusMap] || { label: status, class: 'bg-gray-100 text-gray-800' };
        
        return (
            <span className={`inline-flex px-2 py-1 text-xs font-medium rounded-full ${statusInfo.class}`}>
                {statusInfo.label}
            </span>
        );
    };

    const getApprovalBadge = (approvalStatus: string) => {
        const approvalMap = {
            pending: { label: '⏳ Menunggu', class: 'bg-yellow-100 text-yellow-800' },
            approved: { label: '✅ Disetujui', class: 'bg-green-100 text-green-800' },
            rejected: { label: '❌ Ditolak', class: 'bg-red-100 text-red-800' },
        };
        const approvalInfo = approvalMap[approvalStatus as keyof typeof approvalMap] || { label: approvalStatus, class: 'bg-gray-100 text-gray-800' };
        
        return (
            <span className={`inline-flex px-2 py-1 text-xs font-medium rounded-full ${approvalInfo.class}`}>
                {approvalInfo.label}
            </span>
        );
    };

    const handleMonthChange = (month: string) => {
        router.get(route('attendances.index'), { ...filters, month }, {
            preserveState: true,
        });
    };

    const handleFilterChange = (key: string, value: string) => {
        router.get(route('attendances.index'), { 
            ...filters, 
            [key]: value,
            month: currentMonth 
        }, {
            preserveState: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Presensi" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <span>📋</span> 
                            {userRole === 'asn' ? 'Presensi Saya' : 
                             userRole === 'operator_opd' ? `Presensi ${opd?.name || 'OPD'}` : 
                             'Presensi Semua OPD'}
                        </h1>
                        <p className="text-gray-600 mt-1">
                            {userRole === 'asn' && 'Kelola dan pantau presensi kehadiran Anda'}
                            {userRole === 'operator_opd' && 'Kelola dan setujui presensi ASN di OPD Anda'}
                            {userRole === 'admin' && 'Pantau presensi ASN di seluruh OPD'}
                        </p>
                    </div>
                    
                    {userRole === 'asn' && (
                        <Link
                            href={route('attendances.create')}
                            className="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium"
                        >
                            <span>➕</span>
                            Catat Presensi
                        </Link>
                    )}
                </div>

                {/* Filters */}
                <div className="bg-white rounded-lg border p-4">
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        {/* Month Filter */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                📅 Bulan
                            </label>
                            <input
                                type="month"
                                value={currentMonth}
                                onChange={(e) => handleMonthChange(e.target.value)}
                                className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            />
                        </div>

                        {/* Status Filter */}
                        {(userRole === 'operator_opd' || userRole === 'admin') && (
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    📊 Status
                                </label>
                                <select
                                    value={filters.status || 'all'}
                                    onChange={(e) => handleFilterChange('status', e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                >
                                    <option value="all">Semua Status</option>
                                    <option value="present">Hadir</option>
                                    <option value="absent">Tidak Hadir</option>
                                    <option value="late">Terlambat</option>
                                    <option value="sick">Sakit</option>
                                    <option value="leave">Cuti</option>
                                    <option value="business_trip">Dinas</option>
                                </select>
                            </div>
                        )}

                        {/* Approval Filter */}
                        {userRole === 'operator_opd' && (
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    ✅ Persetujuan
                                </label>
                                <select
                                    value={filters.approval || 'all'}
                                    onChange={(e) => handleFilterChange('approval', e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                >
                                    <option value="all">Semua</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                        )}

                        {/* OPD Filter (Admin only) */}
                        {userRole === 'admin' && (
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    🏢 OPD
                                </label>
                                <select
                                    value={filters.opd || 'all'}
                                    onChange={(e) => handleFilterChange('opd', e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                >
                                    <option value="all">Semua OPD</option>
                                    {opds.map((opdItem) => (
                                        <option key={opdItem.id} value={opdItem.id}>
                                            {opdItem.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        )}
                    </div>
                </div>

                {/* Attendance Table */}
                <div className="bg-white rounded-lg border overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full">
                            <thead className="bg-gray-50 border-b">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {userRole !== 'asn' ? 'ASN' : 'Tanggal'}
                                    </th>
                                    {userRole !== 'asn' && (
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            OPD
                                        </th>
                                    )}
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Persetujuan
                                    </th>
                                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {attendances.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={userRole === 'asn' ? 5 : 7} className="px-6 py-12 text-center">
                                            <div className="text-gray-500">
                                                <span className="text-4xl mb-4 block">📋</span>
                                                <p className="text-lg font-medium mb-2">Belum ada data presensi</p>
                                                <p className="text-sm">
                                                    {userRole === 'asn' 
                                                        ? 'Mulai catat presensi kehadiran Anda'
                                                        : 'Belum ada presensi untuk periode ini'
                                                    }
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                ) : (
                                    attendances.data.map((attendance) => (
                                        <tr key={attendance.id} className="hover:bg-gray-50">
                                            {userRole !== 'asn' && (
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div className="text-sm font-medium text-gray-900">
                                                            {attendance.user?.profile?.full_name || attendance.user?.name}
                                                        </div>
                                                        <div className="text-sm text-gray-500">
                                                            {attendance.user?.nip}
                                                        </div>
                                                    </div>
                                                </td>
                                            )}
                                            {userRole === 'admin' && (
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className="text-sm text-gray-900">
                                                        {attendance.user?.opd?.name || 'N/A'}
                                                    </span>
                                                </td>
                                            )}
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {new Date(attendance.date).toLocaleDateString('id-ID', {
                                                        day: '2-digit',
                                                        month: 'short',
                                                        year: 'numeric'
                                                    })}
                                                </div>
                                                <div className="text-sm text-gray-500">
                                                    {new Date(attendance.date).toLocaleDateString('id-ID', {
                                                        weekday: 'long'
                                                    })}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">
                                                    {attendance.check_in && (
                                                        <div>⏰ Masuk: {attendance.check_in}</div>
                                                    )}
                                                    {attendance.check_out && (
                                                        <div>🏠 Keluar: {attendance.check_out}</div>
                                                    )}
                                                    {!attendance.check_in && !attendance.check_out && (
                                                        <span className="text-gray-500">-</span>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {getStatusBadge(attendance.status)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {getApprovalBadge(attendance.approval_status)}
                                                {attendance.approver && (
                                                    <div className="text-xs text-gray-500 mt-1">
                                                        oleh {attendance.approver.name}
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div className="flex items-center justify-end gap-2">
                                                    <Link
                                                        href={route('attendances.show', attendance.id)}
                                                        className="text-blue-600 hover:text-blue-900"
                                                    >
                                                        👁️ Detail
                                                    </Link>
                                                    {userRole === 'asn' && attendance.approval_status === 'pending' && (
                                                        <>
                                                            <span className="text-gray-300">|</span>
                                                            <Link
                                                                href={route('attendances.edit', attendance.id)}
                                                                className="text-green-600 hover:text-green-900"
                                                            >
                                                                ✏️ Edit
                                                            </Link>
                                                        </>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {attendances.data.length > 0 && attendances.links && (
                        <div className="bg-gray-50 px-4 py-3 border-t">
                            <div className="flex items-center justify-between">
                                <div className="text-sm text-gray-700">
                                    Menampilkan {attendances.meta.from} - {attendances.meta.to} dari {attendances.meta.total} data
                                </div>
                                <div className="flex gap-2">
                                    {attendances.links.map((link: PaginationLink, index: number) => {
                                        if (link.url === null) {
                                            return (
                                                <span
                                                    key={index}
                                                    className="px-3 py-2 text-sm text-gray-400 cursor-not-allowed"
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            );
                                        }

                                        return (
                                            <Link
                                                key={index}
                                                href={link.url}
                                                className={`px-3 py-2 text-sm rounded ${
                                                    link.active
                                                        ? 'bg-blue-600 text-white'
                                                        : 'bg-white text-gray-700 border hover:bg-gray-50'
                                                }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}