<?php
            $user_sync_key   = $this->options['key'];
            $user_sync_url_c = $this->options['central_url'];
            $disabled = '';

            if ( "" != $user_sync_url_c )
                $disabled = 'readonly="readonly"';

        ?>
            <script type="text/javascript">
                jQuery( document ).ready( function() {
                    jQuery( "#user_sync_form" ).submit( function () {
                        if ( "" == jQuery( "#user_sync_url_c" ).val() && "uninstall" != jQuery( "#usync_action" ).val( ) ) {
                            alert( "<?php _e( 'Bitte trage die URL der Master-Seite ein', 'user-sync' ) ?>" );
                            return false;
                        }

                        if ( "" == jQuery( "#user_sync_key" ).val() && "uninstall" != jQuery( "#usync_action" ).val( ) ) {
                            alert( "<?php _e( 'Bitte trage den Schlüssel der Master-Seite ein', 'user-sync' ) ?>" );
                            return false;
                        }

                        return true;
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

                <h2><?php _e( 'Subseite-Einstellungen', 'user-sync' ) ?></h2>
                <h3><?php _e( 'Alle Benutzerdaten von der Master-Seite werden mit dieser Seite synchronisiert.', 'user-sync' ) ?></h3>
                <form method="post" action="" id="user_sync_form">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <?php _e( 'URL der Master-Seite:', 'user-sync' ) ?>
                            </th>
                            <td>
                                <input type="text" name="user_sync_url_c" id="user_sync_url_c" value="<?php echo $user_sync_url_c; ?>" size="50" <?php echo $disabled; ?> />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <?php _e( 'Schlüssel der Master-Seite:', 'user-sync' ) ?>
                            </th>
                            <td>
                                <input type="text" name="user_sync_key" id="user_sync_key" value="<?php echo $user_sync_key; ?>" size="50" <?php echo $disabled; ?> />
                            </td>
                        </tr>
                    <?php if ( "" == $disabled ) {?>
                        <tr valign="top">
                            <th scope="row">
                                <?php _e( 'Standardeinstellungen:', 'user-sync' ) ?>
                            </th>
                            <td>
                                <span class="description">
                                    <?php _e( 'Du kannst diese Einstellungen später auf der Master-Seite ändern.', 'user-sync' ) ?>
                                </span>
                                <br />
                                <label>
                                    <input type="checkbox" name="replace_user" value="1" />
                                    <?php _e( 'Gelöschte Benutzer nicht ersetzen', 'user-sync' ) ?>
                                </label>
                                <br />
                                <label>
                                    <input type="checkbox" name="overwrite_user" value="1" />
                                    <?php _e( 'Überschreibe keine vorhandenen Benutzer (füge sie als zusätzliche Benutzer hinzu).', 'user-sync' ) ?>
                                </label>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                    <?php if ( "" == $disabled ) {?>
                        <p><?php _e( "Hinweis: Sei vorsichtig, wenn Du vorhandene Benutzer überschreibst, da alle vorhandenen Benutzer und ihre Kennwörter ersetzt werden, wenn auf der Unterwebseite derselbe Benutzername vorhanden ist.", 'user-sync' ) ?></p>
                    <?php }?>

                    <?php if ( "" == $disabled ) {?>
                        <?php
                        //safe mode notice
                        if ( isset( $this->safe_mode_notice ) ):?>
                            <span style="color: red;" ><?php _e( 'Achtung: Abgesicherter Modus!', 'user-sync' ); ?></span>
                            <img class="tooltip_img" src="<?php echo $this->plugin_url . "images/"; ?>info_small.png" title="<?php echo $this->safe_mode_notice; ?>"/>
                            <br />
                        <?php endif; ?>
                        <p class="submit">
                            <input type="hidden" name="usync_action" id="usync_action" value="sub_site" />
                            <input type="submit" class="button" value="<?php _e( 'Verbinde diese Seite mit der Master-Seite und führe eine vollständige Synchronisierung durch', 'user-sync' ) ?>" />
                        </p>

                    <?php } else {?>
                        <p class="submit">
                            <input type="hidden" name="usync_action" id="usync_action" value="remove_settings" />
                            <input type="submit" class="button button-secondary" value="<?php _e( 'Trenne die Verbindung zur Master-Seite', 'user-sync' ) ?>" />
                            <span class="description"><?php _e( 'Trenne die Synchronisierung mit der Master-Seite', 'email-newsletter' ) ?></span>
                        </p>
                        <?php } ?>
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
                        </div>

                        <p>
                            <?php _e( "Ausführliche Anweisungen zur Verwendung findest Du unter:", 'user-sync' ) ?><br />
                            <a href="https://n3rds.work/piestingtal-source-project/ps-benutzer-sync/" target="_blank" ><?php _e( 'Anweisungen zur Installation und Verwendung der WordPress-Benutzersynchronisierung.', 'user-sync' ) ?></a>
                        </p>

                </form>
            </div>