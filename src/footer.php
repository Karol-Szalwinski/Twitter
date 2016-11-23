<div>
    <h4>MyTwitter 2016 by Karol Szałwiński powered by CodersLab</h4>
</div>

<?php
//Zamykam połączenia z bazą tam gdzie zostało otworzone
if(isset($conn)) {
    $conn->close();
    $conn = null;
}


