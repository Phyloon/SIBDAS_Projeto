<?php 
require_once 'includes/funcoes.php';
session_start();
redirect_if_not_logged(); 

// 2. Check Role
// Change 'admin' to whatever the string is in your database for full access
if ($_SESSION['role'] !== 'tech' && $_SESSION['role'] !== 'ceo') {
    // If they aren't an admin, kick them out or show an error
    header('Location: ../private/views/staff.php'); 
    exit; // VERY IMPORTANT: stop the script here
}
?>

<?php include 'includes/header.php'?>

    <div class="d-flex">
        
    <?php include 'includes/nav.php'?>

        <div id="content">
            
        <?php include 'includes/topbar.php'?>

            <div class="container-fluid p-4">
                
            

                <div class="row g-4">
                    <div class="col-xl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-4">Upcoming Appointments</h5>
                                <div class="appointment-list">
                                    <div class="appointment-list-item d-flex align-items-center">
                                        <div class="date-badge me-3">
                                            <div class="small text-muted">Jan</div>
                                            <div class="fw-bold">13</div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">Defibrillator Model X - Maintenance</div>
                                            <div class="small text-muted">Room 201 • Hospital Wing A</div>
                                        </div>
                                        <span class="status-badge bg-primary text-white">Pending</span>
                                    </div>
                                    <div class="appointment-list-item d-flex align-items-center">
                                        <div class="date-badge me-3">
                                            <div class="small text-muted">Jan</div>
                                            <div class="fw-bold">14</div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">MRI Scanner - Coil Replacement</div>
                                            <div class="small text-muted">Radiology Dept</div>
                                        </div>
                                        <span class="status-badge bg-warning text-dark">Urgent</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-4">Equipment Status Overview</h5>
                                <div style="height: 250px;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "includes/footer.php"?>