<?php
    require_once('tcpdf/tcpdf.php');
    include 'includes/database.php'; // Hakikisha hili file lina connection ya database

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $programme_id = $_POST['programme_id'];

    // Pata jina la programu
    // Hapa tunaweza kutumia prepared statement kwa usalama zaidi
    $stmt = $conn->prepare("SELECT programme_name FROM tbl_programmes WHERE prog_id = ?");
    $stmt->bind_param("i", $programme_id); // "i" kwa integer
    $stmt->execute();
    $prog_result = $stmt->get_result();
    $programme = $prog_result->fetch_assoc()['programme_name'];
    $stmt->close();

    // Pata siku na muda wote
    class CustomPDF extends TCPDF {
        protected $angle = 0;
        // Page header
        public function Header() {
            // Set font
            $this->SetFont('helvetica', '', 10);
            
            // Add logo
            $this->Image('images/Nit_header_for_pdf.png', 5, 6, 200);
            
            // Line break for spacing
            $this->Ln(20);
        }

        // Page footer
        public function Footer() {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            
            // Page number
            $this->Line(10, 285, 205, 285); 
            $this->Cell(0, 10, 'NATIONAL INSTITUTE OF TRANSPORT ', 0, 0, 'C');
            $this->Ln(4);
            $this->Cell(0, 10, 'CAMPUS EVENT SCHEDULE SYSTEM', 0, 0, 'L');
            $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
            
        }

        function _endpage() {
            if ($this->angle != 0) {
                $this->angle = 0;
                $this->_out('Q');
            }
            parent::_endpage();
        }
    }
    

    // Anzisha PDF
    $pdf = new CustomPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Institution');
    $pdf->SetTitle('Timetable - ' . $programme);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->AddPage();

    // Weka kichwa cha ratiba
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Ln(40);
    
                    // Nimebadilisha query hii pia kutumia prepared statement
                    $stmt_prog_ids = $conn->prepare("SELECT DISTINCT(programme_id) FROM tbl_programme_timetable WHERE programme_id = ?");
                    $stmt_prog_ids->bind_param("i", $programme_id);
                    $stmt_prog_ids->execute();
                    $sql_prog_ids = $stmt_prog_ids->get_result();

                    $programme_ids = [];
                    if ($sql_prog_ids->num_rows > 0) {
                      while ($rows = $sql_prog_ids->fetch_assoc()) {
                            $programme_ids[] = $rows['programme_id'];
                      }
                    }
                    $stmt_prog_ids->close();

                    $time_slots = ["7:00-9:00", "9:00-11:00", "11:00-13:00", "13:00-15:00", "15:00-17:00", "17:00-19:00","19:00-21:00"];
                    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                    
                    
                    foreach ($programme_ids as $prog_id) {
                        // Nimebadilisha query hii pia kutumia prepared statement
                        $stmt_info = $conn->prepare("SELECT programme_name FROM tbl_programmes WHERE prog_id = ?");
                        $stmt_info->bind_param("i", $prog_id);
                        $stmt_info->execute();
                        $check_info = $stmt_info->get_result();
                        $info = $check_info->fetch_assoc();
                        $stmt_info->close();

                        $tbl = "<h3 style='text-align: center;'>Programme Name: ".htmlspecialchars($info['programme_name']) ."</h3>";
                        
                        
                        $tbl .="
                            <style>
                                table, th, td{
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                    padding: 5px; /* Ongeza padding kwa usomaji mzuri */
                                    text-align: center; /* Weka maandishi katikati */
                                }
                                th {
                                    background-color: #f2f2f2; /* Rangi nyepesi kwa header */
                                }
                            </style>
                        ";
                        $tbl .='<table >
                            <tr>
                                <th>Day</th>';
                                foreach($time_slots as $slot){ $tbl .="<th>".htmlspecialchars($slot)."</th>";}
                            $tbl.='</tr>';
                            
                                foreach($days as $day){
                                    $tbl.= "<tr>";
                                    $tbl.= "<td>".htmlspecialchars($day)."</td>";

                                    foreach ($time_slots as $slot) {
                                        // Hapa pia tunatumia prepared statements kwa query ya ratiba
                                        $stmt_timetable = $conn->prepare("SELECT course_name, room_name FROM tbl_programme_timetable WHERE programme_id = ? AND day = ? AND time_slot = ?");
                                        $stmt_timetable->bind_param("iss", $prog_id, $day, $slot); // i kwa int, ss kwa string
                                        $stmt_timetable->execute();
                                        $sql_timetable = $stmt_timetable->get_result();

                                        if ($sql_timetable->num_rows > 0) {
                                            $rows = $sql_timetable->fetch_assoc();
                                            // Rangi ya kijani kwa vipindi
                                            $tbl.="<td style='background-color: #94b8b8; color: white;'>".htmlspecialchars($rows['course_name'])."<br><small>".htmlspecialchars($rows['room_name'])."</small></td>";
                                        }else{
                                            // Rangi ya 'danger' kwa sehemu zisizo na vipindi (FREE)
                                            $tbl.= "<td style='background-color: #F2DEDE; color: #A94442;'><span>FREE</span></td>";
                                        }
                                        $stmt_timetable->close();
                                    }
                                    $tbl.= "</tr>";
                                }
                            
                       $tbl.=" </table>";
                    
                   }

    // Ongeza Table kwenye PDF
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // Download PDF
    $pdf->Output("Timetable_$programme.pdf", 'D');
}
?>