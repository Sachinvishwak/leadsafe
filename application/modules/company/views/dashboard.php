<section id="widget-grid" class="">
    <!-- Widgets -->
    <div class="row clearfix">
        
        <!--- company manage --->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('admin/crew_member'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div class="content">
                        <div class="text">Crew Member</div>
                        <div class="number count-to"><?php 
                        
                        $memberData = $this->db->select('company_member_relations.type, crew_member.*')
                            ->from('company_member_relations')
                            ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                            ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                            ->where('company_member_relations.type','crew')->get()->num_rows();
                        
                        echo $memberData; ?></div>
                    </div>
                </div>
            </a>
        </div>
        <!--- Project --->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('admin/project'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="content">
                        <div class="text">Project</div>
                        <div class="number count-to"><?php 
                        echo $this->common_model->get_total_count('project',array('company_id'=>$_SESSION['company_sess']['id'])); ?></div>
                    </div>
                </div>
            </a>
        </div>
        <!--- sub contractor  --->
        <!--<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('admin/contractor'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-tags"></i>
                    </div>
                    <div class="content">
                        <div class="text">Sub Contractor</div>
                        <div class="number count-to"><?php 
                        echo $this->common_model->get_total_count('contractor',array('is_role'=>2,'company_id'=>$_SESSION['company_sess']['id'])  ); ?></div>
                    </div>
                </div>
            </a>
        </div> -->
        <!--- Lead contractor  --->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('admin/contractor'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div class="content">
                        <div class="text">Lead Contractor</div>
                        <div class="number count-to"><?php
                            $memberData = $this->db->select('company_member_relations.type, contractor.*')
                                ->from('company_member_relations')
                                ->join('contractor', 'company_member_relations.member_id = contractor.id')
                                ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                                ->where('contractor.is_role',1)
                                ->where('company_member_relations.type','leadcontractor')->distinct()->get()->num_rows();
                            
                            echo $memberData;
                        ?></div>
                    </div>
                </div>
            </a>
        </div>
        <!--- Client --->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="<?php echo base_url('admin/client'); ?>">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="content">
                        <div class="text">Client</div>
                        <div class="number count-to"><?php        
                        $memberData = $this->db->select('company_member_relations.type, client.*')
                            ->from('company_member_relations')
                            ->join('client', 'company_member_relations.member_id = client.id')
                            ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                            ->where('company_member_relations.type','client')->order_by("id", "desc")->get()->num_rows();
                        echo $memberData; ?></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- #END# Widgets -->
</section>
<!-- end widget grid-->