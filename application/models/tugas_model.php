<?php

/**
 * Class Model untuk resource Tugas
 * 
 * @package Elearning Dokumenary
 * @link    http://www.dokumenary.net
 */
class Tugas_model extends CI_Model
{

    

    /**
     * Method untuk menghapus data pertanyaan
     * 
     * @param  integer $id
     * @return boolan  true jika berhasil
     * @author Almazari <almazary@gmail.com>
     */
    public function delete_pertanyaan($id)
    {
        $id = (int)$id;

        $this->db->where('id', $id);
        $this->db->delete('tugas_pertanyaan');
        return true;
    }

    /**
     * Method untuk mendapatkan banyak data pertanyaan
     * 
     * @param  integer      $no_of_records
     * @param  integer      $page_no      
     * @param  integer|null $tugas_id     
     * @return array
     * @author Almazari <almazary@gmail.com>
     */
    public function retrieve_all_pertanyaan(
        $no_of_records = 10, 
        $page_no       = 1,
        $tugas_id      = null
    ) {
        $no_of_records = (int)$no_of_records;
        $page_no       = (int)$page_no;

        $where = array();
        if (!is_null($tugas_id)) {
            $tugas_id = (int)$tugas_id;
            $where['tugas_id'] = array($tugas_id, 'where');
        }

        $orderby = array('urutan', 'ASC');

        $data = $this->pager->set('tugas_pertanyaan', $no_of_records, $page_no, $where, $orderby);

        return $data;
    }

    /**
     * Method untuk mendapatkan satu data pertanyaan
     * 
     * @param  integer $id
     * @return array
     * @author Almazari <almazary@gmail.com>
     */
    public function retrieve_pertanyaan($id)
    {
        $id = (int)$id;

        $this->db->where('id', $id);
        $result = $this->db->get('tugas_pertanyaan', 1);
        return $result->row_array();
    }

    /**
     * Method untuk memperbaharui pertanyaan
     * 
     * @param  integer $id        
     * @param  string  $pertanyaan
     * @param  integer $urutan    
     * @param  integer $tugas_id  
     * @return boolean true jika berhasil
     * @author Almazari <almazary@gmail.com>            
     */
    public function update_pertanyaan(
        $id,
        $pertanyaan,
        $urutan,
        $tugas_id
    ) {
        $id       = (int)$id;
        $urutan   = (int)$urutan;
        $tugas_id = (int)$tugas_id;

        $data = array(
            'pertanyaan' => $pertanyaan,
            'urutan'     => $urutan,
            'tugas_id'   => $tugas_id
        );
        $this->db->where('id', $id);
        $this->db->update('tugas_pertanyaan', $data);
        return true;
    }

    /**
     * Method untuk menambah pertanyaan
     * 
     * @param  string  $pertanyaan
     * @param  integer $tugas_id  
     * @return integer last insert id
     * @author Almazari <almazary@gmail.com>
     */
    public function create_pertanyaan(
        $pertanyaan,
        $tugas_id
    ) {
        $tugas_id = (int)$tugas_id;

        $query = $this->db->query("SELECT MAX(urutan) AS max FROM tugas_pertanyaan WHERE tugas_id = $tugas_id");
        $row = $query->row_array();
        if (!isset($row['max']) OR empty($row['max'])) {
            $row['max'] = 1;
        } else {
            $row['max'] = $row['max'] + 1;
        }

        $data = array(
            'pertanyaan' => $pertanyaan,
            'urutan'     => $row['max'],
            'tugas_id'   => $tugas_id
        );
        $this->db->insert('tugas_pertanyaan', $data);
        return $this->db->insert_id();
    }

    /**
     * Method untuk mengambil banyak data tugas upload 
     * 
     * @param  integer       $no_of_records
     * @param  integer       $page_no      
     * @param  integer|null  $tugas_id     
     * @param  integer|null  $siswa_id     
     * @return array
     * @author Almazari <almazary@gmail.com>               
     */
    public function retrieve_all_upload(
        $no_of_records = 10, 
        $page_no       = 1,
        $tugas_id      = null,
        $siswa_id      = null
    ) {
        $no_of_records = (int)$no_of_records;
        $page_no       = (int)$page_no;

        $where = array();
        if (!is_null($tugas_id)) {
            $tugas_id = (int)$tugas_id;
            $where['tugas_id'] = array($tugas_id, 'where');
        }
        if (!is_null($siswa_id)) {
            $siswa_id = (int)$siswa_id;
            $where['siswa_id'] = array($siswa_id, 'where');
        }
        $orderby = array('id' => 'DESC');

        $data = $this->pager->set('upload', $no_of_records, $page_no, $where, $orderby);

        return $data;
    }

    /**
     * Method untuk mendapatkan satu record tugas upload
     * 
     * @param  integer $id
     * @return array
     * @author Almazari <almazary@gmail.com> 
     */
    public function retrieve_upload($id, $tugas_id = null, $siswa_id = null)
    {
        $id = (int)$id;

        $this->db->where('id', $id);
        if (!is_null($tugas_id)) {
            $tugas_id = (int)$tugas_id;
            $this->db->where('tugas_id', $tugas_id);
        }
        if (!is_null($siswa_id)) {
            $siswa_id = (int)$siswa_id;
            $this->db->where('siswa_id', $siswa_id);
        }
        $result = $this->db->get('upload', 1);
        return $result->row_array();
    }

    /**
     * Method untuk insert data tugas upload
     * 
     * @param  string  $file    
     * @param  integer $tugas_id
     * @param  integer $siswa_id
     * @return integer last insert id
     * @author Almazari <almazary@gmail.com>        
     */
    public function create_upload(
        $file,
        $tugas_id,
        $siswa_id
    ) {
        $tugas_id = (int)$tugas_id;
        $siswa_id = (int)$siswa_id;

        $data = array(
            'file'       => $file,
            'tgl_upload' => date('Y-m-d H:i:s'),
            'tugas_id'   => $tugas_id,
            'siswa_id'   => $siswa_id
        );
        $this->db->insert('upload', $data);
        return $this->db->insert_id();
    }

    /**
     * Method untuk menghapus tugas
     * 
     * @param  integer $id
     * @return boolean true jika berhasil
     * @author Almazari <almazary@gmail.com>
     */
    public function delete($id)
    {
        $id = (int)$id;

        $this->db->where('id', $id);
        $this->db->delete('tugas');
        return true;
    }

    /**
     * Method untuk mengambil banyak data tugas
     * 
     * @param  integer       $no_of_records
     * @param  integer       $page_no      
     * @param  integer|null  $mapel_ajar_id
     * @param  integer|null  $type_id      
     * @return array
     * @author Almazari <almazary@gmail.com>
     */
    public function retrieve_all(
        $no_of_records = 10, 
        $page_no       = 1,
        $mapel_ajar_id = null,
        $type_id       = null
    ) {
        $no_of_records = (int)$no_of_records;
        $page_no       = (int)$page_no;

        $where = array();
        if (!is_null($mapel_ajar_id)) {
            $mapel_ajar_id = (int)$mapel_ajar_id;
            $where['mapel_ajar_id'] = array($mapel_ajar_id, 'where');
        }
        if (!is_null($type_id)) {
            $type_id = (int)$type_id;
            $where['type_id'] = array($type_id, 'where');
        }

        $orderby = array('id' => 'DESC');

        $data = $this->pager->set('tugas', $no_of_records, $page_no, $where, $orderby);

        return $data;
    }

    /**
     * Method untuk mendapatkan satu record tugas
     * 
     * @param  integer $id           
     * @param  integer $mapel_ajar_id
     * @param  integer $type_id      
     * @return array
     * @author Almazari <almazary@gmail.com>
     */
    public function retrieve($id, $mapel_ajar_id = null, $type_id = null)
    {
        $id = (int)$id;
        if (!is_null($mapel_ajar_id)) {
            $mapel_ajar_id = (int)$mapel_ajar_id;
            $this->db->where('mapel_ajar_id', $mapel_ajar_id);
        }
        if (!is_null($type_id)) {
            $type_id = (int)$type_id;
            $this->db->where('type_id', $type_id);
        }
        
        $this->db->where('id', $id);
        $result = $this->db->get('tugas', 1);
        return $result->row_array();
    }

    /**
     * Method untuk memperbaharui tugas
     *
     * @param  integer $id
     * @param  integer $mapel_ajar_id
     * @param  integer $type_id      
     * @param  string  $judul        
     * @param  string  $info         
     * @param  string  $tgl_buka     
     * @param  string  $tgl_tutup    
     * @param  integer $durasi       
     * @return boolean true jika berhasil
     * @author Almazari <almazary@gmail.com>  
     */
    public function update(
        $id,
        $mapel_ajar_id,
        $type_id,
        $judul,
        $info      = '',
        $tgl_buka  = null,
        $tgl_tutup = null,
        $durasi    = null
    ) {
        $id            = (int)$id;
        $mapel_ajar_id = (int)$mapel_ajar_id;
        $type_id       = (int)$type_id;

        $data = array(
            'mapel_ajar_id' => $mapel_ajar_id,
            'type_id'       => $type_id,
            'judul'         => $judul,
            'info'          => $info,
            'tgl_buka'      => $tgl_buka,
            'tgl_tutup'     => $tgl_tutup,
            'durasi'        => $durasi
        );
        $this->db->where('id', $id);
        $this->db->update('tugas', $data);
        return true;
    }

    /**
     * Method untuk menambah tugas
     * 
     * @param  integer $mapel_ajar_id
     * @param  integer $type_id      
     * @param  string  $judul        
     * @param  string  $info         
     * @param  string  $tgl_buka     
     * @param  string  $tgl_tutup    
     * @param  integer $durasi       
     * @return integer last insert id
     * @author Almazari <almazary@gmail.com>               
     */
    public function create(
        $mapel_ajar_id,
        $type_id,
        $judul,
        $info      = '',
        $tgl_buka  = null,
        $tgl_tutup = null,
        $durasi    = null
    ) {
        $mapel_ajar_id = (int)$mapel_ajar_id;
        $type_id       = (int)$type_id;

        $data = array(
            'mapel_ajar_id' => $mapel_ajar_id,
            'type_id'       => $type_id,
            'judul'         => $judul,
            'info'          => $info,
            'tgl_buka'      => $tgl_buka,
            'tgl_tutup'     => $tgl_tutup,
            'durasi'        => $durasi
        );
        $this->db->insert('tugas', $data);
        return $this->db->insert_id();
    }

}