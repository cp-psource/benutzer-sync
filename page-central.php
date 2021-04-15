<?php
            $user_sync_key      = $this->options['key'];
            $user_sync_sub_urls = $this->options['sub_urls'];
            $user_sync_siteur   = site_url('', 'admin');

?>
            <script type="text/javascript">
                jQuery( document ).ready( function() {
                    jQuery.fn.editSettings = function ( id ) {

                        if ( "Save" == jQuery( this ).val() ) {
                            jQuery( this ).attr( 'disabled', true );

                            var sub_url = jQuery( "#sub_url_" + id ).val();

                            if (true == jQuery( "#replace_user_" + id ).attr( 'checked' ) )
                                replace_user = 1;
                            else
                                replace_user = 0;

                            if (true == jQuery( "#overwrite_user_" + id ).attr( 'checked' ) )
                                overwrite_user = 1;
                            else
                                overwrite_user = 0;
                            jQuery( "#loading_" + id ).show();
                            jQuery.ajax({
                               type: "POST",
                               url: "<?php echo $user_sync_siteur;?>/wp-admin/admin-ajax.php",
                               data: "action=user_sync_settings&url=" + sub_url + "&replace_user=" + replace_user + "&overwrite_user=" + overwrite_user,
                               success: function(){
                                 jQuery( "input[value=Save]" ).val( 'Edit' );
                                 jQuery( "input[value=Edit]" ).attr( 'disabled', false );
                                 jQuery( "#loading_" + id ).hide();
                               }
                             });
                            jQuery( "#sub_list input.sett" ).attr( 'disabled', true );
                            jQuery( "#sub_list label" ).attr( 'class', 'description' );

                            return;

                        }

                        jQuery( "#sub_list input.sett" ).attr( 'disabled', true );
                        jQuery( "#sub_list label" ).attr( 'class', 'description' );

                        if ( "Edit" == jQuery( this ).val() ) {
                            jQuery( "#settings_" + id + " input" ).attr( 'disabled', false );
                            jQuery( "#settings_" + id + " label" ).attr( 'class', '' );
                            jQuery( this ).val('Close');
                            jQuery( "input[value=Edit]" ).attr( 'disabled', true );
                            return;
                        }

                        if ( "Close" == jQuery( this ).val() ) {
                            jQuery( this ).val('Edit');
                            jQuery( "input[value=Edit]" ).attr( 'disabled', false );
                            return;
                        }
                    };

                    jQuery( "#sub_list label" ).click(function () {
                        if ( ! jQuery( this ).find( 'input.sett' ).attr( 'disabled' ) ) {
                            jQuery( "input[value=Close]" ).val( 'Save' );
                        }

                    });


                    jQuery(".tooltip_img[title]").tooltip();


                    //uninstall plugin options
                    jQuery( "#uninstall_yes" ).click( function() {
                        jQuery( "#usync_action" ).val( "uninstall" );
                        jQuery( "#user_sync_form" ).submit();
                        return false;
                    });

                    jQuery( "#uninstall" ).click( function() {
                        jQuery( "#uninstall_confirm" ).show( );
                        return false;
                    });

                    jQuery( "#uninstall_no" ).click( function() {
                        jQuery( "#uninstall_confirm" ).hide( );
                        return false;
                    });


                });
            </script>
            <div class="wrap">
                <?php
                //Debug mode
                if ( isset( $this->options['debug_mode'] ) && '1' == $this->options['debug_mode'] ):?>
                <div class="updated fade"><p><?php _e( 'Der Debug-Modus ist aktiviert.', 'user-sync' ); ?></p></div>
                <?php endif; ?>
                <h2><?php _e( 'Master Seite-Einstellungen', 'user-sync' ) ?></h2>
                <h3><?php _e( 'Alle Benutzerdaten von dieser Master-Seite werden mit verbundenen Subsites synchronisiert.', 'user-sync' ) ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <?php _e( 'URL der Master-Site:', 'user-sync' ) ?>
                        </th>
                        <td>
                            <input type="text" name=""  value="<?php echo $user_sync_siteur; ?>" readonly="readonly" size="50" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <?php _e( 'Sicherheitsschlüssel:', 'user-sync' ) ?>
                        </th>
                        <td>
                            <input type="text" name=""  value="<?php echo $user_sync_key; ?>" readonly="readonly" size="50" />
                        </td>
                    </tr>
                </table>

                <br />
            </div>
            <span class="description"><?php _e( "Um eine Unterwebseite zu erstellen, installiere einfach dieses Plugin auf der Unterwebseite, aktiviere es, mache diese Webseite zu einer Unterwebseite und gib die URL (einschließlich https) und den hier angegebenen Schlüssel ein.", 'user-sync' ) ?></span>
            <p><?php _e( 'Sei vorsichtig, wenn Du vorhandene Benutzer überschreibst, da alle vorhandenen Benutzer und ihre Kennwörter ersetzt werden, wenn auf der Unterwebsite derselbe Benutzername vorhanden ist.', 'user-sync' ) ?></p>
            <p><?php _e( 'Das Hinzufügen eines neuen Benutzers oder das Vornehmen von Änderungen am Benutzer oder seinen Details auf der Master-Seite werden automatisch mit Unterwebseiten synchronisiert.', 'user-sync' ) ?></p>
            <p><?php _e( "'Jetzt alle Seiten synchronisieren' wird verwendet, wenn Du eine Unterwebseite-Einstellung bearbeiten und Benutzer aktualisieren musst.", 'user-sync' ) ?></p>
            <p><?php _e( 'Um zu verhindern, dass eine Unterwebseite mit der Master-Seite synchronisiert wird, melde Dich einfach bei der Unterwebseite an, gehe zum Menü Benutzersynchronisierung und klicke auf die Schaltfläche "Von Master-Seite trennen" oder auf die Schaltfläche "Optionen deinstallieren".', 'user-sync' ) ?></p>
            <p>
                <?php _e( "Ausführliche Anweisungen zur Verwendung findest Du unter:", 'user-sync' ) ?><br />
                <a href="https://n3rds.work/piestingtal-source-project/ps-benutzer-sync/" target="_blank" ><?php _e( 'Anweisungen zur Installation und Verwendung der WordPress-Benutzersynchronisierung.', 'user-sync' ) ?></a>
            </p>

            <?php
            //safe mode notice
            if ( isset( $this->safe_mode_notice ) ):?>
                <p>
                    <span style="color: red;" ><?php _e( 'Achtung: Abgesicherter Modus!', 'user-sync' ); ?></span>
                    <img class="tooltip_img" src="<?php echo $this->plugin_url . "images/"; ?>info_small.png" title="<?php echo $this->safe_mode_notice; ?>"/>
                </p>
            <?php endif; ?>

            <p><?php _e( 'Registrierte Subseiten:', 'user-sync' ) ?></p>
            <?php if($user_sync_sub_urls) { ?>
            <form method="post" action="" id="sub_list">
                <table width="700px" class="widefat post fixed" style="width:95%;">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                #
                            </th>
                            <th>
                                <?php _e( 'URLs', 'user-sync' ) ?>
                            </th>
                            <th>
                                <?php _e( 'Letzte Synchronisierung', 'user-sync' ) ?>
                            </th>
                            <th  style="width: 400px;">
                                <?php _e( 'Einstellungen', 'user-sync' ) ?>
                            </th>
                            <th>
                                <?php _e( 'Aktion', 'user-sync' ) ?>
                            </th>
                        </tr>
                    </thead>
                <?php
                $user_sync_i = 0;
                if ( $user_sync_sub_urls )
                    foreach( $user_sync_sub_urls as $one) {
                        if ($user_sync_i % 2 == 0)
                        {
                            echo "<tr class='alternate'>";
                        } else {
                            echo "<tr class='' >";
                        }
                        $user_sync_i++;
                ?>
                        <td style="vertical-align: middle;">
                           <?php echo $user_sync_i; ?>
                        </td>
                        <td style="vertical-align: middle;">
                           <?php echo $one['url']; ?>
                           <input type="hidden" id="sub_url_<?php echo $user_sync_i;?>"  value="<?php echo base64_encode($one['url']);?>"/>
                        </td>
                        <td style="vertical-align: middle;">
                        <?php echo $one['last_sync']; ?>
                        </td>
                        <td style="vertical-align: middle;">

                            <div class="settings_block" id="settings_<?php echo $user_sync_i;?>" >
                                <div class="loading_image" id="loading_<?php echo $user_sync_i;?>" ></div>
                                <label class="description" >
                                    <input type="checkbox" class="sett" name="replace_user" id="replace_user_<?php echo $user_sync_i;?>" value="1" disabled="disabled" <?php if ( "1" == $one['param']['replace_user']) echo 'checked="checked"'; ?> />
                                    <?php _e( 'Gelöschte Benutzer nicht ersetzen', 'user-sync' ) ?>
                                </label>
                                <br />
                                <label class="description">
                                    <input type="checkbox" class="sett" name="overwrite_user" id="overwrite_user_<?php echo $user_sync_i;?>" value="1" disabled="disabled" <?php if ( "1" == $one['param']['overwrite_user']) echo 'checked="checked"'; ?> />
                                    <?php _e( 'Überschreibe keine vorhandenen Benutzer (füge sie als zusätzliche Benutzer hinzu).', 'user-sync' ) ?>
                                </label>
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                        <input type="button" class="button button-secondary" name="edit_button" id="actin_button_<?php echo $user_sync_i;?>" value="Edit" onclick="jQuery(this).editSettings( <?php echo $user_sync_i;?> );" />
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </table>
            </form>

            <br />
            <center>
            <form method="post" action="">
                <input type="hidden" name="usync_action" value="sync_all" />
                <input type="submit" id="user-sync-sync-all" class="button button-primary" value="<?php _e( 'Synchronisiere jetzt alle Webseiten', 'user-sync' ) ?>"  />
                <span id="user-sync-spinner" class="spinner spinner-save"></span>
            </form>
            </center>
            <br />
            <?php
            }
            else {
            ?>
            <p class="description"><?php _e( 'Auf dieser Master-Seite sind keine Unterwebseiten registriert', 'user-sync' ) ?></p>
            <?php
            }
            ?>
            </p>

            <form method="post" action="" id="user_sync_form">
                <input type="hidden" name="usync_action" id="usync_action" value="sync_all" />
                <div class="submit">
                    <input type="button" class="button" id="uninstall" style="color: red;" value="<?php _e( 'Deinstallationsoptionen', 'user-sync' ) ?>" />
                    <span class="description"><?php _e( "Lösche alle Plugin-Optionen aus der Datenbank.", 'email-newsletter' ) ?></span>
                    <div id="uninstall_confirm" style="display: none;">
                        <p>
                            <span class="description"><?php _e( 'Bist du sicher?', 'email-newsletter' ) ?></span>
                        </p>
                        <p>
                            <input type="button" class="button" name="uninstall" id="uninstall_no" value="<?php _e( 'Nein', 'email-newsletter' ) ?>" />
                            <input type="button" class="button" name="uninstall" id="uninstall_yes" value="<?php _e( 'Ja', 'email-newsletter' ) ?>" />
                        </p>
                    </div>
                </p>
            </form>
