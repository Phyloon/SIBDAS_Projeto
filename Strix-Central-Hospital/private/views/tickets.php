<?php include '../includes/header.php'?>

    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <!--Page content-->
        <div id="content">

            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>
            
            <!--Dpt. Acordions-->
            <div class="accordion mt-4" id="ticketsAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Cardiology Department
                            <span class="badge bg-danger rounded-pill ms-2">3</span>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#ticketsAccordion">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ECG Machine - Not powering on
                                    <span class="badge bg-danger rounded-pill">High</span>
                                </li>
                            </ul>
                        </div>
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ECG Machine - Not powering on
                                    <span class="badge bg-danger rounded-pill">High</span>
                                </li>
                            </ul>
                        </div>
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
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Surgery Department
                            <span class="badge bg-danger rounded-pill ms-2">1</span>
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
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            Therapy Department
                            <span class="badge bg-danger rounded-pill ms-2">2</span>
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#ticketsAccordion">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ECG Machine - Not powering on
                                    <span class="badge bg-danger rounded-pill">High</span>
                                </li>
                            </ul>
                        </div>
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
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>  