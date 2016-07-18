<?php $this->load->view('index/index');?>
            <div class="col-xs-9 col-md-9">
                <div class="keyword-result">
                    <h3>Keyword suggestion</h3>
                    <table cellpadding="0" cellspacing="0" class="tbl_result" id="google-tbl">
                        <tr>
                            <td width="40"><input type="checkbox" name="" ></td>
                            <td width="290">Keywords <i class="fa fa-question" aria-hidden="true"></i></td>
                            <td width="150">Search Volume <i class="fa fa-question" aria-hidden="true"></i></td>
                            <td width="130">CPC <i class="fa fa-question" aria-hidden="true"></i></td>
                            <td width="220">AdWords Competition <i class="fa fa-question" aria-hidden="true"></i></td>
                        </tr>
                        <?php foreach ($data as $key=>$value){ ?>
                        <tr>

                            	<td width="40"><input type="checkbox" name="<?php echo $value['keyword'] ?>" ></td>
                                <td width="290"><?php echo $value['keyword'] ?></td>
                                <td width="150"><?php echo $value['volume'] ?></td>
                                <td width="130"><?php echo $value['avg_cpc'] ?></td>
                                <td width="220"><?php echo $value['competition'] ?></td>

                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</section>
</body>
</html>