`<?php`

`$conn = new mysqli("localhost", "hbuser", "hbpass", "hbtask");`

`$sql = "SELECT username FROM meibo WHERE number = 1";
$result = $conn->query($sql);`

`$row = $result->fetch_assoc();`

`echo "私の名前は " . $row["username"] . " です。";`

`$conn->close();`

`?>`