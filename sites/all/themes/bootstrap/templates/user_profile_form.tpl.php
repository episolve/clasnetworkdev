    <div class="profile-edit-form-container">
        <?php if (!bootstrap_theme_is_contributor($account)) : ?>
        <div class="profile-become-contributor">
            <strong>Become a Contributor</strong>
            <p>As a contributor ..... Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ornare id nulla quis molestie. Pellentesque odio ante, semper vel sodales vel, molestie vitae leo. Pellentesque in quam quis nisi condimentum commodo quis nec diam. Suspendisse cursus, velit ac ornare volutpat, velit orci tincidunt nunc, at sodales massa felis at dui. Fusce at justo metus. Suspendisse aliquet aliquam posuere. Suspendisse viverra dolor quis convallis accumsan. Aliquam commodo imperdiet lectus sit amet lacinia. Curabitur non condimentum tellus.</p>
        </div>
        <?php endif; ?>
        <?php if (!bootstrap_theme_is_contributor($account)) : ?>
        <div class="profile-step">1.&nbsp;&nbsp;Complete Your Profile</div>
        <?php endif; ?>
        <div class="profile-fields-info">
            <div class="profile-field-requried">Required Information:</div>
            <div class="profile-field firstname">
                <?php echo drupal_render($form['field_first_name']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field lastname">
                <?php echo drupal_render($form['field_last_name']); ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="profile-field">
                <?php echo drupal_render($form['account']['mail']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field password">
                <?php echo drupal_render($form['account']['current_pass']); ?>
                <div class="profile-change-password"><a href="#">Change Password</a></div>
                <div class="clear"></div>
            </div>
            <div class="profile-fields-change-password">
                <div class="profile-field">
                    <?php echo drupal_render($form['account']['pass']['pass1']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field">
                    <?php echo drupal_render($form['account']['pass']['pass2']); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="profile-field">
                <?php echo drupal_render($form['field_location']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field">
                <?php echo drupal_render($form['field_institution']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field">
                <?php echo drupal_render($form['field_title']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field multiple">
                <label class="control-label">Discipline</label>
                <?php echo drupal_render($form['field_discipline']); ?>
                <div class="clear"></div>
            </div>
            <div class="profile-field">
                <label class="control-label">Account Type:</label>
                <div class="clear"></div>
            </div>
        </div>
        <div class="profile-field-picture">
            <div class="profile-field-badges">Badges:</div>
            <?php echo drupal_render($form['picture']); ?>
        </div>
        <div class="clear"></div>
        <div class="profile-fields-others">
            <div class="profile-fields-etc">
                <div class="profile-develop-category">
                    <strong>Develop Your Profile</strong><br />
                    This information is not required and you can fill it out at a later time. This information will be displayed on your public profile.
                </div>
                <div class="profile-field multiple">
                    <label class="control-label">Affiliations</label>
                    <?php echo drupal_render($form['field_affiliations']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field multiple">
                    <label class="control-label">Accretions</label>
                    <?php echo drupal_render($form['field_accretions']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field multiple">
                    <label class="control-label">Languages</label>
                    <?php echo drupal_render($form['field_languages']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field multiple">
                    <label class="control-label">Expertise</label>
                    <?php echo drupal_render($form['field_expertise']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field multiple">
                    <label class="control-label">Education</label>
                    <?php echo drupal_render($form['field_education']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field textarea">
                    <?php echo drupal_render($form['field_publications']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field textarea">
                    <?php echo drupal_render($form['field_experience']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field">
                    <?php echo drupal_render($form['field_office']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field">
                    <?php echo drupal_render($form['field_cell']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field textarea">
                    <?php echo drupal_render($form['field_work_address']); ?>
                    <div class="clear"></div>
                </div>
                <div class="profile-field action">
                    <?php echo drupal_render($form['actions']['submit']); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="profile-fields-social">
                <strong>Connect Your Account</strong>
                <ul>
                    <li class="facebook"><a href="#"></a></li>
                    <li class="twitter"><a href="#"></a></li>
                    <li class="googleplus"><a href="#"></a></li>
                    <li class="linkedin"><a href="#"></a></li>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
        <?php if (!bootstrap_theme_is_contributor($account)) : ?>
        <div class="profile-step step2">2.&nbsp;&nbsp;Submit!</div>
        <div class="profile-step-desc">
            The approval process takes ...... Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ornare id nulla quis molestie. Pellentesque odio ante, semper vel sodales vel, molestie vitae leo. Pellentesque in quam quis nisi condimentum commodo quis nec diam. Suspendisse cursus, velit ac ornare volutpat, velit orci tincidunt nunc, at sodales massa felis at dui. Fusce at justo metus. Suspendisse aliquet aliquam posuere. Suspendisse viverra dolor quis convallis accumsan. Aliquam commodo imperdiet lectus sit amet lacinia. Curabitur non condimentum tellus.
        </div>
        <div class="profile-step-button profile-to-become-contributor"><button type="submit" class="button" data-url="<?php echo url('user/'.$account->uid.'/to-become-contributor'); ?>">Submit</button></div>
        <?php endif; ?>
    </div>
<?php
    unset($form['field_first_name']);
    unset($form['field_last_name']);
    unset($form['account']['mail']);
    unset($form['account']['current_pass']);
    unset($form['field_institution']);
    unset($form['field_title']);
    unset($form['field_discipline']);
    unset($form['picture']);
    unset($form['field_affiliations']);
    unset($form['field_accretions']);
    unset($form['field_languages']);
    unset($form['field_expertise']);
    unset($form['field_education']);
    unset($form['field_publications']);
    unset($form['field_experience']);
    unset($form['field_office']);
    unset($form['field_cell']);
    unset($form['field_work_address']);
    unset($form['actions']['submit']);

    echo '<div class="element-invisible">'.drupal_render_children($form).'</div>';
?>