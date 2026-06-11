<?php include '../includes/header.php'?>

    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <div id="content">

            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold">Quick Contacts</h3>
                </div>

                <div class="accordion mt-4" id="ticketsAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Receiving role: Doctor
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#ticketsAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Receiving role: Nurse
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#ticketsAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                Receiving role: Technician
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#ticketsAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Receiving role: Surgeon
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#ticketsAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ECG Machine - Not powering on
                                        <span class="badge bg-danger rounded-pill">High</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>  