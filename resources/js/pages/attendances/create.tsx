import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Presensi',
        href: '/attendances',
    },
    {
        title: 'Catat Presensi',
        href: '/attendances/create',
    },
];

interface AttendanceFormData {
    date: string;
    check_in: string;
    check_out: string;
    status: string;
    notes: string;
    [key: string]: string;
}

export default function AttendanceCreate() {
    const { data, setData, post, processing, errors } = useForm<AttendanceFormData>({
        date: new Date().toISOString().split('T')[0], // Today's date
        check_in: '',
        check_out: '',
        status: 'present',
        notes: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(route('attendances.store'));
    };

    const getCurrentTime = () => {
        const now = new Date();
        return now.toTimeString().slice(0, 5); // HH:MM format
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Catat Presensi" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                {/* Header */}
                <div className="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <h1 className="text-2xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <span>📝</span> Catat Presensi
                    </h1>
                    <p className="text-gray-600">
                        Catat kehadiran Anda untuk hari ini atau hari sebelumnya
                    </p>
                </div>

                {/* Form */}
                <div className="bg-white rounded-lg border p-6">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Date */}
                        <div>
                            <label htmlFor="date" className="block text-sm font-medium text-gray-700 mb-2">
                                📅 Tanggal *
                            </label>
                            <input
                                type="date"
                                id="date"
                                value={data.date}
                                onChange={(e) => setData('date', e.target.value)}
                                max={new Date().toISOString().split('T')[0]} // Can't select future dates
                                className={`w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                    errors.date ? 'border-red-500' : 'border-gray-300'
                                }`}
                                required
                            />
                            {errors.date && <p className="mt-1 text-sm text-red-600">{errors.date}</p>}
                        </div>

                        {/* Status */}
                        <div>
                            <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-2">
                                📊 Status Kehadiran *
                            </label>
                            <select
                                id="status"
                                value={data.status}
                                onChange={(e) => setData('status', e.target.value)}
                                className={`w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                    errors.status ? 'border-red-500' : 'border-gray-300'
                                }`}
                                required
                            >
                                <option value="present">✅ Hadir</option>
                                <option value="late">⏰ Terlambat</option>
                                <option value="sick">🤒 Sakit</option>
                                <option value="leave">🏖️ Cuti</option>
                                <option value="business_trip">🚗 Dinas Luar</option>
                                <option value="absent">❌ Tidak Hadir</option>
                            </select>
                            {errors.status && <p className="mt-1 text-sm text-red-600">{errors.status}</p>}
                        </div>

                        {/* Time Fields - Only show for present/late status */}
                        {(data.status === 'present' || data.status === 'late') && (
                            <div className="grid gap-4 md:grid-cols-2">
                                {/* Check In */}
                                <div>
                                    <label htmlFor="check_in" className="block text-sm font-medium text-gray-700 mb-2">
                                        ⏰ Waktu Masuk
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="time"
                                            id="check_in"
                                            value={data.check_in}
                                            onChange={(e) => setData('check_in', e.target.value)}
                                            className={`flex-1 border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                                errors.check_in ? 'border-red-500' : 'border-gray-300'
                                            }`}
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setData('check_in', getCurrentTime())}
                                            className="px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                        >
                                            Sekarang
                                        </button>
                                    </div>
                                    {errors.check_in && <p className="mt-1 text-sm text-red-600">{errors.check_in}</p>}
                                </div>

                                {/* Check Out */}
                                <div>
                                    <label htmlFor="check_out" className="block text-sm font-medium text-gray-700 mb-2">
                                        🏠 Waktu Keluar
                                    </label>
                                    <div className="flex gap-2">
                                        <input
                                            type="time"
                                            id="check_out"
                                            value={data.check_out}
                                            onChange={(e) => setData('check_out', e.target.value)}
                                            className={`flex-1 border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                                errors.check_out ? 'border-red-500' : 'border-gray-300'
                                            }`}
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setData('check_out', getCurrentTime())}
                                            className="px-3 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200"
                                        >
                                            Sekarang
                                        </button>
                                    </div>
                                    {errors.check_out && <p className="mt-1 text-sm text-red-600">{errors.check_out}</p>}
                                </div>
                            </div>
                        )}

                        {/* Notes */}
                        <div>
                            <label htmlFor="notes" className="block text-sm font-medium text-gray-700 mb-2">
                                📝 Catatan (Opsional)
                            </label>
                            <textarea
                                id="notes"
                                rows={3}
                                value={data.notes}
                                onChange={(e) => setData('notes', e.target.value)}
                                placeholder="Tambahkan catatan jika diperlukan..."
                                className={`w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                    errors.notes ? 'border-red-500' : 'border-gray-300'
                                }`}
                            />
                            {errors.notes && <p className="mt-1 text-sm text-red-600">{errors.notes}</p>}
                        </div>

                        {/* Info Box */}
                        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div className="flex items-start gap-3">
                                <span className="text-2xl">ℹ️</span>
                                <div>
                                    <h4 className="font-semibold text-blue-900 mb-1">Informasi Penting</h4>
                                    <ul className="text-sm text-blue-800 space-y-1">
                                        <li>• Presensi yang sudah dicatat akan menunggu persetujuan dari Operator OPD</li>
                                        <li>• Anda dapat mengedit presensi yang masih berstatus "Menunggu"</li>
                                        <li>• Waktu masuk dan keluar hanya diperlukan untuk status "Hadir" atau "Terlambat"</li>
                                        <li>• Pastikan data yang dimasukkan sudah benar sebelum menyimpan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-between pt-4 border-t">
                            <button
                                type="button"
                                onClick={() => window.history.back()}
                                className="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                            >
                                ← Kembali
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className={`inline-flex items-center gap-2 px-6 py-2 rounded-lg text-white font-medium ${
                                    processing
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-blue-600 hover:bg-blue-700'
                                }`}
                            >
                                {processing ? (
                                    <>
                                        <span className="animate-spin">⏳</span>
                                        Menyimpan...
                                    </>
                                ) : (
                                    <>
                                        <span>💾</span>
                                        Simpan Presensi
                                    </>
                                )}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}