<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Graph extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Graph_model');
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['profiles'] = $this->Graph_model->get_profiles();
        $this->load->view('graph/index', $data);
    }

    public function get_data()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        // Validasi input
        if (empty($type) || empty($id) || empty($start_date) || empty($end_date)) {
            echo json_encode(['error' => 'Semua field harus diisi']);
            return;
        }

        $data['graph_data'] = $this->Graph_model->get_graph_data($type, $id, $start_date, $end_date);
        echo json_encode($data);
    }

    public function download_report()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        // Validasi input
        if (empty($type) || empty($id) || empty($start_date) || empty($end_date)) {
            show_error('Semua field harus diisi untuk mengunduh laporan', 400);
            return;
        }

        $data = $this->Graph_model->get_graph_data($type, $id, $start_date, $end_date);

        // Debugging: Log data
        log_message('debug', 'Graph data for report: ' . print_r($data, true));

        $dompdf = new Dompdf();
        $html = $this->load->view('graph/report', ['graph_data' => $data], TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('f4', 'portrait');
        $dompdf->render();
        $dompdf->stream("report_" . date('Ymd') . ".pdf", array("Attachment" => true));
    }
}