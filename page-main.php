            <script type="text/javascript">
                jQuery( document ).ready( function() {
                    jQuery.fn.makeChose = function ( id ) {
                        if ( 1 == id )
                            jQuery( "#user_sync_status" ).val( 'central' );
                        else
                            jQuery( "#user_sync_status" ).val( 'sub' );

                        jQuery( "#user_sync_form" ).submit();
                    };
                });
            </script>

            <div class="wrap">
                <h2><?php _e( 'Seiten-Typ', 'user-sync' ) ?></h2>
                <p><?php _e( "Die Benutzersynchronisierung funktioniert, indem eine Seite zu einer 'Master'-Seite und alle anderen Seiten zu 'Sub' gemacht werden.", 'user-sync' ) ?></p>
                <p><?php _e( 'Wenn Du alle Benutzer dieser Seite mit anderen Seiten synchronisieren möchtest, mache diese zu einer Master-Seite.', 'user-sync' ) ?></p>
                <p><?php _e( 'Wenn Du jedoch die Benutzer von anderen Seiten mit dieser Seite synchronisieren möchtest, mache diese zu einer Sub-Seite.', 'user-sync' ) ?></p>
                <p><?php _e( 'Du kannst diese Optionen später jederzeit ändern, indem Du das Plugin deaktivierst und anschließend wieder aktivierst.', 'user-sync' ) ?></p>
                <p><?php _e( 'NB: Du musst mindestens eine Master-Seite haben!', 'user-sync' ) ?></p>
                <p>
                    <?php _e( "Ausführliche Anweisungen zur Verwendung findest Du unter:", 'user-sync' ) ?><br />
                    <a href="https://cp-psource.github.io/ps-support/" target="_blank" ><?php _e( 'Anweisungen zur Installation und Verwendung der WordPress-Benutzersynchronisierung.', 'user-sync' ) ?></a>
                </p>

                <?php
                //safe mode notice
                if ( isset(  $this->safe_mode_notice ) ) {
                   echo '<div id="message" class="error fade"><p> '. $this->safe_mode_notice . '</p></div>';
                }
                ?>

                <form method="post" action="" id="user_sync_form">
                    <input type="hidden" name="usync_action" value="install" />
                    <input type="hidden" name="user_sync_status" id="user_sync_status" value="" />

                                <p class="debug_message" >

                                    <?php _e( 'Hinweis: Wenn Du Probleme mit Synchronisierungsbenutzern hast, kannst Du den Debug-Modus zum Schreiben einiger Vorgänge in die Protokolldatei verwenden. Zum Schreiben benötigst Du den Ordner "plugins/user-sync/log/" beschreibbar. Was tun mit Protokolldateien, die Du in der Anleitung des Plugins lesen kannst erfährst', 'user-sync' );  ?>
                                    <a href="https://cp-psource.github.io/ps-support/" target="_blank" ><?php _e( 'hier', 'user-sync' ) ?></a>
                                    <br /><br />
                                    <input type="checkbox" name="debug" id="debug" value="1" />
                                    <label for="debug"><?php _e( 'Verwende den Debug-Modus', 'user-sync' ) ?></label>

                                </p>

                            <p>
                            <input type="button" class="button" value="<?php _e( 'Mache diese Seite zur Master-Seite', 'user-sync' ) ?> " onclick="jQuery(this).makeChose( 1 );" />
                            <input type="button" class="button" value="<?php _e( 'Mache diese Seite zu einer Sub-Seite', 'user-sync' ) ?> " onclick="jQuery(this).makeChose( 2 );" />
                            </p>
                </form>
            </div>