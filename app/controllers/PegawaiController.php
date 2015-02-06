<?php

class PegawaiController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        $data = array(
            'field' => array('nip', 'nama_pegawai', 'golongan', 'nama_status_pegawai'),
            'values' => Pegawai::orderBy('nama_pegawai')->get()
        );
        return Response::json($data);
    }

    public function getKeluarga($id = null) {
        $data = array(
            'field' => array('nama_anggota_keluarga', 'tanggal_lahir', 'status_kawin', 'pekerjaan'),
            'values' => Keluarga::orderBy('nama_anggota_keluarga')->where('id_pegawai', '=', $id)->get()
        );
        return Response::json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
        $data = array(
            'status' => StatusPegawai::DropdownStatusPegawai(),
            'golongan' => Golongan::DropdownGolongan(),
            'jabatan' => Jabatan::DropdownJabatan(),
            'unitkerja' => UnitKerja::DropdownUnit(),
            'satuankerja' => SatuanKerja::DropdownSatKer(),
            'lokasikerja' => LokasiKerja::DropdownLokasiKerja(),
            'eselon' => Eselon::DropdownEselon(),
            'statusjabatan' => StatusJabatan::DropdownStatusJabatan()
        );
        return Response::json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
        $destinationPath = public_path() . '/upload';
        $data = Input::except('file');
        $data['tanggal_lahir'] = $this->formatDate($data['tanggal_lahir']);
        $data['tanggal_pengangkatan_cpns'] = $this->formatDate($data['tanggal_pengangkatan_cpns']);
        $data["tanggal_sk_pangkat"] = $this->formatDate($data['tanggal_sk_pangkat']);
        $data["tanggal_mulai_pangkat"] = $this->formatDate($data['tanggal_mulai_pangkat']);
        $data["tanggal_selesai_pangkat"] = $this->formatDate($data['tanggal_selesai_pangkat']);
        $pegawai = new Pegawai($data);
        if (Input::hasFile('file')) {
            Input::file('file')->move($destinationPath);
            $pegawai->foto = Input::file('file')->getClientOriginalName();
        }
        if ($pegawai->save()) {
            return Response::json(array('success' => TRUE));
        };
    }

    public function storeKeluarga() {
        $data = Input::All();
        $data['tanggal_lahir'] = $this->formatDate($data['tanggal_lahir']);
        if (isset($data['tanggal_nikah']))
            $data['tanggal_nikah'] = $this->formatDate($data['tanggal_nikah']);
        if (isset($data['tanggal_cerai_meninggal']))
            $data['tanggal_cerai_meninggal'] = $this->formatDate($data['tanggal_cerai_meninggal']);
        $keluarga = new Keluarga($data);
        if ($keluarga->save()) {
            return Response::json(array('success' => TRUE));
        }
    }

    private function formatDate($array) {
        $telo = explode(' ', $array);
        $kampret = $telo[2] . ' ' . $telo[1] . ' ' . $telo[3];
        $string = date('d/m/Y', strtotime($kampret));
        return $string;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //

        $pegawai = Pegawai::find($id);
        return Response::json($pegawai);
    }

    public function editKeluarga($id) {
        $keluarga = Keluarga::find($id);
        return Response::json($keluarga);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    public function updateKeluarga($id) {
        $data = Input::All();
        $keluarga = Keluarga::find($id);
        if ($keluarga->update($data)) {
            return Response::json(array('success' => TRUE));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
        $keluarga = Keluarga::find($id);
        if($keluarga->delete()){
            return Response::json(array('success' => TRUE));
        }
    }

}
