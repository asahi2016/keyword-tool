<!DOCTYPE html>
<?php
$CI = &get_instance();
?>
            <?php $this->load->view('index/index'); ?>
            <div class="col-xs-9 col-md-9">
                <div class="keyword-result">
                    <h3>Keyword suggestion</h3>
                    <p> Search for "<?php echo $bing['keyword'];?>" found <b><?php echo $bing['count']; ?></b> unique keywords </p>

                    <input id="copy" type="button" value="Copy">
                    <input id="copy_all" type="button" value="Copy All"/>

                    <div id="table-div-id" class="table-div-class">
                    <table cellpadding="0" cellspacing="0" class="tbl_result search-form" id="bing-tbl">
                        <?php
                                if(isset($bing['result']) && !empty($bing['result'])){ ?>
                                        <thead>
                                             <tr>
                                                <td width="40"><input type="checkbox" id="select_all" value="select"></td>
                                                <td width="290">Keywords <i class="fa fa-question" aria-hidden="true"></i></td>
                                            </tr>
                                        </thead>
                                    <tbody>
                                <?php foreach($bing['result'] as $k => $val) { ?>

                                             <tr>
                                                <td width="40"><input type="checkbox" class="checkbox-key" name="checkbox-key" value="<?php echo $val; ?>" ></td>
                                                <td width="290"><?php echo $val; ?></td>

                                            </tr>

                                <?php }}else{?>
                                    </tbody>
                                        <div class="bing-no-keyword">
                                            <h3>Unfortunately we could not find any keyword suggestions for your query. Please try searching for another term.</h3>
                                            </div>

                               <?php } ?>

                    </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
</body>
</html>