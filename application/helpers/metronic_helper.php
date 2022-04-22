<?php

function card($data, $content)
{

    ob_start(); ?>
    <?php foreach ($data as $key) {
    ?>
        <?php if (isset($key)) { ?>
            <form method="post" action="<?= base_url(@$key['action'])  ?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="card-icon">
                                <i class="<?= @$key['icon'] ?>"></i>
                            </span>
                            <h3 class="card-label">
                                <?= @$key['title'] ?>
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <?php if (isset($key['button'])) { ?>
                                <a class="btn btn-<?= $key['button']['button_color'] ?>" href="<?= base_url($key['button']['button_link']) ?>" role="button"><?= $key['button']['button_title'] ?></a>
                            <?php } else {
                            } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (is_array($content)) { ?>
                            <?php foreach ($content as $val) { ?>
                                <?= $val ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?= $content ?>
                        <?php  } ?>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <?php if (isset($key['button_cancel'])) { ?>
                                <a class="btn btn-<?= $key['button_cancel']['button_color'] ?>" href="<?= base_url($key['button_cancel']['button_action']) ?>" role="button"><?= $key['button_cancel']['button_title'] ?></a>
                            <?php } else {
                            } ?>
                            <?php if (isset($key['button_save'])) { ?>
                                <button type="submit" class="btn btn-<?= $key['button_save']['button_color'] ?> ml-2"><?= $key['button_save']['button_title'] ?></button>
                            <?php } else {
                            } ?>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    <?php } ?>
<?php $contents = ob_get_clean();
    return $contents;
}

function nav($data)
{
    ob_start(); ?>
    <ul class="nav nav-pills nav-primary mt-5 mb-5">
        <?php $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);
        ?>
        <?php foreach ($data as $val) { ?>
            <li class="nav-item">
                <a <?= $uri_segments[2] == $val['nav_link'] ? 'class="nav-link active"' : 'class="nav-link"' ?> href="<?= base_url($val['nav_link']) ?>">
                    <span class="nav-icon">
                        <i class="<?= $val['nav_icon'] ?>"></i>
                    </span>
                    <span class="nav-text"><?= $val['nav_title'] ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php $contents = ob_get_clean();
    return $contents;
}

function search($data)
{
    ob_start(); ?>
    <?php foreach ($data as $val) { ?>
        <div class="mb-3" id="search-nav">
            <div class="navbar-left navbar-form nav-search mr-md-3">
                <div class="input-group">
                    <input id="<? $val['id'] ?>" type="text" class="form-control form-control-solid ps-14 col-3 border border-secondary" name="<?= $val['name'] ?>" value="" placeholder="Search..." data-kt-search-element="input">
                    <div class="input-group-prepend bg-primary rounded-right">
                        <button type="submit" class="btn btn-search pr-2">
                            <i class="fa fa-search search-icon" style="color: white;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4 my-2 my-md-0">
            <div class=" mb-3" id="search-nav">
                <div class="navbar-left navbar-form nav-search mr-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend bg-light-secondary ">
                            <button type="submit" class="btn btn-search pr-1">
                                <span><i class="fa fa-search search-icon "></i></span>
                            </button>
                        </div>
                        <input type="text" name="<?= $val['name'] ?>" id="<? $val['id'] ?>" placeholder="Search ..." class="form-control">
                    </div>
                </div>
            </div>
        </div> -->
    <?php } ?>
<?php $contents = ob_get_clean();
    return $contents;
}

function data_table($table)
{
    ob_start(); ?>
    <div class="card  shadow-md p-5">
        <div class="table-responsive ">
            <table class="table table-hover">
                <thead class="bg-gray-light">
                    <?php foreach ($table['t_head'] as $index) {
                    ?>
                        <tr>
                            <?php foreach ($index as $key) { ?>
                                <th><?= $key ?></th>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </thead>
                <tbody>
                    <?php
                    if (isset($table['t_body'])) {
                        foreach ($table['t_body'] as $value) {
                    ?>
                            <tr>
                                <?php foreach ($value as $key) {
                                ?>
                                    <td><?= $key ?></td>
                                <?php } ?>
                            </tr>
                    <?php }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $contents = ob_get_clean();
    return $contents;
}

function pagination($data)
{
    foreach ($data as $val) {
        $config['base_url'] = $val['base_url'];
        $config['total_rows'] = $val['total_rows'];
        $config['per_page'] =  $val['per_page'];
        $config['num_links'] = 3;

        $config['first_link']       = '<i class="ki ki-bold-double-arrow-back icon-xs"></i>';
        $config['last_link']        = '<i class="ki ki-bold-double-arrow-next icon-xs"></i>';
        $config['next_link']        = '<i class="ki ki-bold-arrow-next icon-xs"></i>';
        $config['prev_link']        = '<i class="ki ki-bold-arrow-back icon-xs"></i>';
        $config['full_tag_open']    = '<div class="d-flex justify-content-between align-items-center flex-wrap"><div class="d-flex flex-wrap py-2 mr-3">';
        $config['full_tag_close']   = '</div></div>';
        $config['num_tag_open']     = '<li class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="btn btn-icon btn-sm border-0 btn-hover-primary active mr-2 my-1">';
        $config['cur_tag_close']    = '</li>';
        $config['next_tag_open']    = '<li class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">';
        $config['next_tagl_close']  = '</li>';
        $config['prev_tag_open']    = '<li class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">';
        $config['prev_tagl_close']  = '</li>';
        $config['first_tag_open']   = '<li class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">';
        $config['first_tagl_close'] = '</li>';
        $config['last_tag_open']    = '<li class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">';
        $config['last_tagl_close']  = '</li>';
    }
    return $config;
}

function button($data)
{
    ob_start(); ?>
    <?php foreach ($data as $value) { ?>
        <a class="btn btn-primary" href="#" role="button">Tambah Data</a>
    <?php } ?>
<?php $contents = ob_get_clean();
    return $contents;
}

function dropdown_action($data)
{
    ob_start(); ?>
    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ki ki-bold-more-hor"></i>
        </a>
        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
            <!--begin::Navigation-->
            <ul class="navi navi-hover">
                <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2"> Choose an action: </li>
                <?php foreach ($data as $val) { ?>
                    <li class="navi-item"> <a href="<?= base_url($val['button_link']) ?>" class="navi-link"> <span class="navi-icon"><i class="<?= $val['button_icon'] ?>"></i></span> <span class="navi-text"><?= $val['button_title'] ?></span> </a> </li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php $contents = ob_get_clean();
    return $contents;
}

function form($data)
{
    ob_start();
?>
    <div class="row">
        <?php foreach ($data as $index) { ?>
            <div class="<?= $index['column'] ?>">
                <div class="form-group">
                    <?php foreach ($index['form'] as $value) { ?>
                        <?php if (isset($value) && @$value['input-type'] == 'form') { ?>
                            <label class="mt-2"><?= @$value['form_title'] ?></label>
                            <input type="<?= $value['type'] ?>" class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" placeholder="<?= $value['place_holder'] ?>">
                            <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error($value['name']) : '' ?></small>
                            <?php if ($value['type'] !== 'hidden') { ?>
                                <small class="form-text text-muted"><?= $value['note'] ?></small>
                            <?php } else {
                            } ?>
                        <?php } else if (isset($value) && $value['input-type'] == 'text-area') { ?>
                            <label class="mt-2" for="comment"><?= $value['form_title'] ?></label>
                            <textarea class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" rows="5" placeholder="<?= $value['place_holder'] ?>"><?= $value['value'] ?></textarea>
                            <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error($value['name']) : '' ?></small>
                            <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                        <?php } else if (isset($value) && $value['input-type'] == 'select') { ?>
                            <label class="mt-2" for="defaultSelect"><?= $value['form_title'] ?></label>
                            <select class="form-control form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>">
                                <?php foreach ($value['data'] as $val) { ?>
                                    <?php if (@$val[$value['content_id']] == @$value['value']) { ?>
                                        <option value="<?= @$val[$value['content_id']] ?>" selected><?= @$val[$value['content']]  ?></option>
                                    <?php }  ?>
                                    <option value="<?= @$val[$value['content_id']] ?>"><?= @$val[$value['content']]   ?></option>
                                <?php } ?>
                            </select>
                            <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error($value['nama']) : '' ?></small>
                            <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                        <?php } elseif (isset($value) && $value['input-type'] == 'form_repeater') { ?>
                            <div id="kt_repeater_1">
                                <div class="form-group row">
                                    <div data-repeater-list="<?= $value['repeater_name'] ?>" class="col-lg-12">
                                        <div data-repeater-item="" class="form-group row">
                                            <?php foreach ($value['form'] as $value) { ?>
                                                <?php if (isset($value) && @$value['input-type'] == 'form') { ?>
                                                    <div class="col-lg-5">
                                                        <label class="mt-2"><?= @$value['form_title'] ?></label>
                                                        <input type="<?= $value['type'] ?>" class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" placeholder="<?= $value['place_holder'] ?>">
                                                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error($value['name']) : '' ?></small>
                                                        <?php if ($value['type'] !== 'hidden') { ?>
                                                            <small class="form-text text-muted"><?= $value['note'] ?></small>
                                                        <?php } else {
                                                        } ?>
                                                    </div>
                                                <?php } else if (isset($value) && $value['input-type'] == 'text-area') { ?>
                                                    <div class="col-lg-5">
                                                        <label class="mt-2" for="comment"><?= $value['form_title'] ?></label>
                                                        <textarea class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" rows="5" placeholder="<?= $value['place_holder'] ?>"><?= $value['value'] ?></textarea>
                                                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error('nama') : '' ?></small>
                                                        <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                                                    </div>
                                                <?php } else if (isset($value) && $value['input-type'] == 'select') { ?>
                                                    <div class="col-lg-5">
                                                        <label class="mt-2" for="defaultSelect"><?= $value['form_title'] ?></label>
                                                        <select class="form-control form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>">
                                                            <?php foreach ($value['data'] as $val) { ?>
                                                                <?php if (@$val[$value['content_id']] == @$value['value']) { ?>
                                                                    <option value="<?= @$val[$value['content_id']] ?>" selected><?= @$val[$value['content']]  ?></option>
                                                                <?php }  ?>
                                                                <option value="<?= @$val[$value['content_id']] ?>"><?= @$val[$value['content']]   ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error('nama') : '' ?></small>
                                                        <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                                                    </div>
                                                <?php } ?>

                                            <?php } ?>
                                            <div class="col-lg-2 mt-10">
                                                <a href="javascript:;" data-repeater-delete="" class="btn font-weight-bold btn-danger btn-icon">
                                                    <i class="la la-remove"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12"></div>
                                    <div class="col">
                                        <div data-repeater-create="" class="btn font-weight-bold btn-primary">
                                            <i class="la la-plus"></i>Add
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

            </div>
        <?php } ?>
    </div>
<?php $contents = ob_get_clean();
    return $contents;
}

function form_repeater($data)
{
    ob_start();
?>
    <div id="kt_repeater_1">
        <div data-repeater-list="">
            <div data-repeater-item="">
                <?php foreach ($data as $value) { ?>
                    <?php if (isset($value) && $value['input-type'] == 'form') { ?>
                        <label class="mt-2"><?= $value['form_title'] ?></label>
                        <input type="<?= $value['type'] ?>" class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" placeholder="<?= $value['place_holder'] ?>">
                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error($value['name']) : '' ?></small>
                        <?php if ($value['type'] !== 'hidden') { ?>
                            <small class="form-text text-muted"><?= $value['note'] ?></small>
                        <?php } else {
                        } ?>
                    <?php } else if (isset($value) && $value['input-type'] == 'text-area') { ?>
                        <label class="mt-2" for="comment"><?= $value['form_title'] ?></label>
                        <textarea class="form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" rows="5" placeholder="<?= $value['place_holder'] ?>"><?= $value['value'] ?></textarea>
                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error('nama') : '' ?></small>
                        <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                    <?php } else if (isset($value) && $value['input-type'] == 'select') { ?>
                        <label class="mt-2" for="defaultSelect"><?= $value['form_title'] ?></label>
                        <select class="form-control form-control" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>">
                            <?php foreach ($value['data'] as $val) { ?>
                                <?php if (@$val[$value['content_id']] == @$value['value']) { ?>
                                    <option value="<?= @$val[$value['content_id']] ?>" selected><?= @$val[$value['content']]  ?></option>
                                <?php }  ?>
                                <option value="<?= @$val[$value['content_id']] ?>"><?= @$val[$value['content']]   ?></option>
                            <?php } ?>
                        </select>
                        <small class="text-danger"><?= $value['validation'] == 'true' ?   form_error('nama') : '' ?></small>
                        <small id="emailHelp2" class="form-text text-muted"><?= $value['note'] ?></small>
                    <?php } ?>
                <?php } ?>
                <div class="row mt-3">
                    <div class="col-lg-3">
                        <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                            <i class="la la-trash-o"></i>Delete</a>
                    </div>
                    <div class="col-lg-3">
                        <a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
                            <i class="la la-plus"></i>Add</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $contents = ob_get_clean();
    return $contents;
}

function button_edit($data)
{
    ob_start();
?>
    <?php foreach ($data as $val) { ?>
        <a class="btn btn-warning btn-sm" href="<?= site_url($val['button']['button_link']) ?>"><?= $val['button']['button_title'] ?></a>
    <?php } ?>
<?php $contents = ob_get_clean();
    return $contents;
}

function button_delete($data)
{
    ob_start();
?>
    <?php foreach ($data as $val) { ?>
        <a class="btn btn-danger btn-sm" href="<?= site_url($val['button']['button_link']) ?>"><?= $val['button']['button_title'] ?></a>
    <?php } ?>
    <?php $contents = ob_get_clean();
    return $contents;
}

function allert($data)
{
    ob_start();
    foreach ($data as $val) {
    ?>
        <!-- Bootstrap Notify -->
        <div class="alert <?= $val['alert_type'] ?>  animate__animated animate__fadeInDown position-absolute mt-3 mr-4" style="right: 0;">
            <?= $val['title'] ?>
            <button type="button" class="close ml-5" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php
        $contents = ob_get_clean();
        return $contents;
    }
}
