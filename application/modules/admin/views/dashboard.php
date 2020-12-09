
<section id="widget-grid" class="">
    <!-- Widgets -->
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('tasks'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-tags"></i>
                    </div>
                    <div class="content">
                        <div class="text">Tasks</div>
                        <div class="number count-to">
                            <?php // echo number_format_short($tasks); ?>
                            <?php 
                                echo $this->common_model->get_total_count('tasks',array('created_by'=>1)); ?>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!--- company manage --->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('company'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-building"></i>
                    </div>
                    <div class="content">
                        <div class="text">Company</div>
                        <div class="number count-to"><?php echo $this->common_model->get_total_count('company'); ?></div>
                    </div>
                </div>
            </a>
        </div>
        <!--  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('jobtype'); ?>">
                <div class="info-box bg-blue hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-bars"></i>
                    </div>
                    <div class="content">
                        <div class="text">Job Types</div>
                        <div class="number count-to"><?php echo number_format_short($jobType); ?></div>
                    </div>
                </div>
            </a>
        </div>   
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('jobs'); ?>">
                <div class="info-box bg-purple hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-tasks"></i>
                    </div>
                    <div class="content">
                        <div class="text">Jobs</div>
                        <div class="number count-to"><?php echo number_format_short($jobs); ?></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('drivers'); ?>">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="content">
                        <div class="text">Drivers</div>
                        <div class="number count-to"><?php echo number_format_short($drivers); ?></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('vehicles'); ?>">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-taxi"></i>
                    </div>
                    <div class="content">
                        <div class="text">Vehicles</div>
                        <div class="number count-to"><?php echo number_format_short($vehicles); ?></div>
                    </div>
                </div>
            </a>
        </div>   
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('reports'); ?>">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-line-chart"></i>
                    </div>
                    <div class="content">
                        <div class="text">Reports</div>
                        <div class="number count-to">3</div>
                    </div>
                </div>
            </a>
        </div> -->
    </div>
    <!-- #END# Widgets -->
</section>
<!-- end widget grid-->