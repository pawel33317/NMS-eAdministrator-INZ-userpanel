<div class="panel panel-primary">
    <div class="panel-heading">Lista moich urządzeń</div>
    <div class="panel-body">	
        <table class="table table-hover"><thead>
                <tr>
                    <th>Nazwa urządzenia</th>
                    <th>Typ urządzenia</th>
                    <th>Adres IP</th>
                    <th>Operacje</th>
                </tr>
            </thead><tbody>
                <?php
                if ($this->userDevices) {
                    foreach ($this->userDevices as $userDevice) {
                        echo '<tr><td>' . $userDevice["devname"] . '</td><td>' . $userDevice["devtype"] . '</td><td>' . $userDevice["ip"] . '</td>'
                        . '<td><a href="'.URL.'deviceregister/deviceDelete/' . $userDevice["id"] . '">Usuń</a></td></tr>';
                    }
                } else {
                    echo '<tr><td>Brak</td><td></td><td></td><td></td></tr>';
                }
                ?>
            </tbody>
        </table></div></div>