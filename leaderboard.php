<?php include 'header.php' ?>

<div class="content">
  <h4>Leaderboard</h4>
     <?php

     $query  = 'SELECT * FROM users ORDER BY wins DESC, losses ASC, ties DESC, id ASC LIMIT 25';
     $result = mysqli_query($dbLink, $query);

     if($result && mysqli_num_rows($result) > 0){
         echo '<table class="table table-striped">';
         echo '<tr>';
         echo '<th>Rank</th>';
         echo '<th>Username</th>';
         echo '<th>Wins</th>';
         echo '<th>Losses</th>';
         echo '<th>Ties</th>';
         echo '<th>Win %</th>';
         echo '</tr>';

         $rank = 0;

         while ($row = mysqli_fetch_array($result)){
             $rank += 1;
             $totalwagers = $row['wins'] + $row['losses'];
             $percentage = 0;
             if ($totalwagers > 0){
                 $percentage = $row['wins'] / $totalwagers * 100;
             }
             $percentageText = number_format((float)$percentage, 1, '.', '') . '%';

             echo '<tr>';
             echo '<td>', $rank, '</td>';
             echo '<td><a href="profile.php?u=', $row['username'], '">', $row['username'], '</a></td>';
             echo '<td>', $row['wins'], '</td>';
             echo '<td>', $row['losses'], '</td>';
             echo '<td>', $row['ties'], '</td>';
             echo '<td>', $percentageText, '</td>';
             echo '</tr>';
         }

         echo '</table>';
     } else {
         echo 'There seems to be an issue retrieving the current leaderboard. Check back again later!';
     }
     ?>

</div>

<?php include 'footer.php' ?>