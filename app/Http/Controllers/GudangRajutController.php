<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangRajutKeluar;
use App\Models\GudangRajutKeluarDetail;
use App\Models\GudangRajutMasuk;
use App\Models\GudangRajutMasukDetail;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\MaterialModel;
use Illuminate\Database\Eloquent\Builder;
use DB;
class GudangRajutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gudangKeluar = GudangRajutKeluar::all();
        $gudangMasuk = GudangRajutMasuk::all();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangRajut.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }

    /* Gudang Rajut Request */
    public function gudangRajutRequest()
    {
        $gRajutRequest = GudangRajutKeluar::all();
        foreach ($gRajutRequest as $request) {
            $cekBahanPembantu = GudangRajutKeluarDetail::where('gdRajutKId', $request->id)
                ->whereHas('material', function (Builder $query) {
                    $query->where('keterangan', 'LIKE', '%Bahan Bantu / Merchandise%');
                })
                ->get();
            if (count($cekBahanPembantu) == 0) {
                $cekPengembalian = GudangRajutMasuk::where('gdRajutKId', $request->id)->first();
                if ($cekPengembalian != null) {
                    $request->cekPengembalian = 1;
                }
            } else {
                $request->cekPengembalian = 2;
            }
        }

        return view('gudangRajut.request.index', ['gRajutRequest' => $gRajutRequest]);
    }

    public function RDetail($id)
    {
        $gRajutRequest = GudangRajutKeluar::where('id', $id)->first();
        $gRajutRequestDetail = GudangRajutKeluarDetail::where('gdRajutKId', $id)->get();
        $cekBahanPembantu = GudangRajutKeluarDetail::where('gdRajutKId', $id)
            ->whereHas('material', function (Builder $query) {
                $query->where('keterangan', 'LIKE', '%Bahan Bantu / Merchandise%');
            })
            ->get();
        foreach ($gRajutRequestDetail as $value) {
            $material = $value->material->nama;
        }

        return view('gudangRajut.request.detail', ['material' => $material, 'gudangKeluar' => $gRajutRequest, 'gudangKeluarDetail' => $gRajutRequestDetail, 'cekBahanPembantu' => $cekBahanPembantu]);
    }

    public function RTerimaBarang($id)
    {
        DB::beginTransaction();

        try {
            $id = $id;
            $statusDiterima = 1;

            $gudangRajutTerima = GudangRajutKeluar::updateStatusDiterima($id, $statusDiterima);

            if ($gudangRajutTerima == 1) {
                DB::commit();

                return redirect('gudangRajut/Request')->with('success', 'Berhasil Menerima Barang');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Gagal Menerima Barang');
            }
        }
    }

    public function Rcreate($id)
    {
        $materials = MaterialModel::get();
        $gRajutRequest = GudangRajutKeluar::where('id', $id)->first();
        $gRajutRequestDetail = GudangRajutKeluarDetail::where('gdRajutKId', $gRajutRequest->id)->get();

        return view('gudangRajut.kembali.create', ['materials' => $materials, 'gRajutRequest' => $gRajutRequest, 'gRajutRequestDetail' => $gRajutRequestDetail]);
    }

    public function Rstore(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->jumlah_data != 0) {
                $gdRajutMasuk = GudangRajutMasuk::CreateRajutMasuk($request->gdRajutKId);
                if ($gdRajutMasuk) {
                    for ($i = 1; $i <= $request->totalData; $i++) {
                        $bahanBaku = GudangBahanBaku::where('id', $request['gudangId_' . $i])
                            ->where('purchaseId', $request['purchaseId_' . $i])
                            ->first();
                        if ($bahanBaku != null) {
                            $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId', $bahanBaku->id)
                                ->where('purchaseId', $bahanBaku->purchaseId)
                                ->where('materialId', $request->materialId)
                                ->first();
                            if ($bahanBakuDetail != null) {
                                for ($j = 0; $j < count($request['gramasi_' . $i]); $j++) {
                                    $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail->id, $request['diameter_' . $i][$j], $request['gramasi_' . $i][$j], 0, $request['berat_' . $i][$j], 0, 0, 'Kg', 0, null, null);
                                    $gudangRajutMasuk = GudangRajutMasukDetail::CreateRajutMasukDetail($request['gudangId_' . $i], $bahanBakuDetailMaterial, $gdRajutMasuk, $request['purchaseId_' . $i], $request->materialId, $request->jenisId, $request['gramasi_' . $i][$j], $request['diameter_' . $i][$j], $request['berat_' . $i][$j], $request['qty_' . $i][$j]);
                                }
                            } else {
                                $bahanBakuDetail = GudangBahanBakuDetail::CreateBahanBakuDetail($bahanBaku->id, $request['purchaseId_' . $i], $request->materialId, 0, 0);
                                if ($bahanBakuDetail) {
                                    for ($j = 0; $j < count($request['gramasi_' . $i]); $j++) {
                                        $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail, $request['diameter_' . $i][$j], $request['gramasi_' . $i][$j], 0, $request['berat_' . $i][$j], 0, 0, 'Kg', 0, null, null);
                                        $gudangRajutMasuk = GudangRajutMasukDetail::CreateRajutMasukDetail($request['gudangId_' . $i], $bahanBakuDetailMaterial, $gdRajutMasuk, $request['purchaseId_' . $i], $request->materialId, $request->jenisId, $request['gramasi_' . $i][$j], $request['diameter_' . $i][$j], $request['berat_' . $i][$j], $request['qty_' . $i][$j]);
                                    }
                                }
                            }
                        }
                    }

                    if ($gudangRajutMasuk == 1) {
                        DB::commit();

                        return redirect('gudangRajut/Kembali')->with('success', 'Data Berhasil Disimpan');
                    }
                }
            } else {
                return redirect()
                    ->back()
                    ->with('msg', 'Silahkan Tambahkan Roll Terlebih Dahulu');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('msg', 'Data Gagal Disimpan');
            }
        }
    }
    /* END Gudang Rajut Request */

    /* Gudang Rajut Kembali */
    public function gudangRajutKembali()
    {
        $gRajutKembali = GudangRajutMasuk::all();
        return view('gudangRajut.kembali.index', ['gRajutKembali' => $gRajutKembali]);
    }

    public function KDetail($id)
    {
        $gRajutKembali = GudangRajutMasuk::where('id', $id)->first();
        $gRajutKembaliDetail = GudangRajutMasukDetail::where('gdRajutMId', $id)
            ->orderBy('purchaseId', 'asc')
            ->get();

        return view('gudangRajut.kembali.detail', ['gudangMasuk' => $gRajutKembali, 'gudangMasukDetail' => $gRajutKembaliDetail]);
    }

    public function RUpdate($id)
    {
        $materials = MaterialModel::get();

        $gRajutKeluar = GudangRajutMasuk::where('id', $id)->first();
        $gRajutRequestDetail = GudangRajutKeluarDetail::where('gdRajutKId', $gRajutKeluar->gdRajutKId)->get();
        foreach ($gRajutRequestDetail as $detail) {
            $detail->rajutKeluar = GudangRajutMasukDetail::where('gdRajutMId', $gRajutKeluar->id)
                ->where('purchaseId', $detail->purchaseId)
                ->get();
        }

        return view('gudangRajut.kembali.update', ['materials' => $materials, 'gRajutKeluar' => $gRajutKeluar, 'gRajutRequestDetail' => $gRajutRequestDetail]);
    }

    public function RUpdateSave(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->jumlah_data != 0) {
                for ($i = 1; $i <= $request->totalData; $i++) {
                    $bahanBaku = GudangBahanBaku::where('id', $request['gudangId_' . $i])
                        ->where('purchaseId', $request['purchaseId_' . $i])
                        ->first();
                    if ($bahanBaku != null) {
                        $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId', $bahanBaku->id)
                            ->where('purchaseId', $bahanBaku->purchaseId)
                            ->where('materialId', $request->materialId)
                            ->first();
                        if ($bahanBakuDetail != null) {
                            for ($j = 0; $j < count($request['gramasi_' . $i]); $j++) {
                                $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail->id, $request['diameter_' . $i][$j], $request['gramasi_' . $i][$j], 0, $request['berat_' . $i][$j], 0, 0, 'Kg', 0, null, null);
                                $gudangRajutMasuk = GudangRajutMasukDetail::CreateRajutMasukDetail($request['gudangId_' . $i], $bahanBakuDetailMaterial, $request->gdRajutMId, $request['purchaseId_' . $i], $request->materialId, $request->jenisId, $request['gramasi_' . $i][$j], $request['diameter_' . $i][$j], $request['berat_' . $i][$j], $request['qty_' . $i][$j]);
                            }
                        } else {
                            $bahanBakuDetail = GudangBahanBakuDetail::CreateBahanBakuDetail($bahanBaku->id, $request['purchaseId_' . $i], $request->materialId, 0, 0);
                            if ($bahanBakuDetail) {
                                for ($j = 0; $j < count($request['gramasi_' . $i]); $j++) {
                                    $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::CreateDetailMaterial($bahanBakuDetail, $request['diameter_' . $i][$j], $request['gramasi_' . $i][$j], 0, $request['berat_' . $i][$j], 0, 0, 'Kg', 0, null, null);
                                    $gudangRajutMasuk = GudangRajutMasukDetail::CreateRajutMasukDetail($request['gudangId_' . $i], $bahanBakuDetailMaterial, $request->gdRajutMId, $request['purchaseId_' . $i], $request->materialId, $request->jenisId, $request['gramasi_' . $i][$j], $request['diameter_' . $i][$j], $request['berat_' . $i][$j], $request['qty_' . $i][$j]);
                                }
                            }
                        }
                    }
                }

                if ($gudangRajutMasuk == 1) {
                    DB::commit();

                    return redirect('gudangRajut/Kembali')->with('success', 'Data Berhasil Diubah');
                }
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Silahkan Tambahkan Roll Terlebih Dahulu');
            }
        } catch (\Throwable $th) {
            DB::rollback();

            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Data Gagal Diubah');
            }
        }
    }

    public function RUpdateDelete($gdRajutMId, $gdRajutMDId)
    {
        DB::beginTransaction();

        try {
            $rajutKeluarDetail = GudangRajutMasukDetail::where('id', $gdRajutMDId)->first();
            $DeleteRajutKeluarDetail = GudangRajutMasukDetail::where('id', $gdRajutMDId)->delete();

            if ($DeleteRajutKeluarDetail) {
                $gdDetailMaterial = GudangBahanBakuDetailMaterial::where('id', $rajutKeluarDetail->gdDetailMaterialId)->delete();

                if ($gdDetailMaterial) {
                    DB::commit();

                    return redirect('gudangRajut/Kembali/update/' . $gdRajutMId);
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();

            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Data Gagal Disimpan');
            }
        }
    }

    public function RDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $gRajutKeluar = GudangRajutMasuk::where('id', $request->gdRajutMId)->first();
            $gRajutKeluarDetail = GudangRajutMasukDetail::where('gdRajutMId', $gRajutKeluar->id)->get();
            $DeleteRajutKeluarDetail = GudangRajutMasukDetail::where('gdRajutMId', $gRajutKeluar->id)->delete();
            if ($DeleteRajutKeluarDetail) {
                foreach ($gRajutKeluarDetail as $detail) {
                    $gdDetailMaterial = GudangBahanBakuDetailMaterial::where('id', $detail->gdDetailMaterialId)->delete();
                }

                if ($gdDetailMaterial) {
                    $gRajutKeluar = GudangRajutMasuk::where('id', $request->gdRajutMId)->delete();
                    if ($gRajutKeluar) {
                        DB::commit();
                        return redirect()->back();
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
    }

    /* END Gudang Rajut Kembali */
}
