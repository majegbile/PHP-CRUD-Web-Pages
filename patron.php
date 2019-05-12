<?php
/*
    Mojeed Ajegbile
    CSCI 5060
    Assignment 5 

 */
    $title="Patron Tool";
    require('database.php');
    include("header.php");
    
?>
<?php
if (isset($_POST["submit"])){
		$term= filter_input(INPUT_POST, 'terms');
		$column= filter_input(INPUT_POST, 'column');
		if ($term===false || strlen(trim($term))===0 || $term===null){
			echo "<p class='error'>Empty </p";
			unset($term);
			exit();
		} 
	$terms="%".$term."%";					
   if($column==="title"){
		$sql="select title, pages, year, author, language from books WHERE title LIKE :terms";
	}
	elseif ($column==="language"){
		$sql="select title, pages, year, author, language from books WHERE language LIKE :terms";
	}
	else{
		$sql="select title, pages, year, author, language from books WHERE author LIKE :terms";
	}  
	$stmt = $db->prepare($sql);
		
	//$stmt->bindValue(':column', $column, PDO::PARAM_STR);
   $stmt->bindValue(':terms', $terms, PDO::PARAM_STR);
	//$stmt->execute();
	if ($stmt->execute() == false) {
		echo "WARNING: error Selecting Books<br>";		
	}
	if ($stmt->rowCount()==0){
		echo "Wrong Query, Please Go Back";
		exit();
	}
	if ($stmt->rowCount()>0):
	$results = $stmt->fetchAll();
	endif;
	$stmt->closeCursor();
	?>
	<div id="items">
		<h2>Current Inventory</h2>
		<table>
			<tr>
				<th>title</th>
				<th>pages</th>
                <th>year</th>
                <th>author</th>
                <th>language</th>
			</tr>
			<?php
				foreach ($results as $row) {
					echo "<tr>";
					echo "<td>", htmlspecialchars($row['title']), "</td>\n";
					echo "<td>", htmlspecialchars($row['pages']), "</td>\n";
                    echo "<td>", htmlspecialchars($row['year']), "</td>\n";
                    echo "<td>", htmlspecialchars($row['author']), "</td>\n";
                    echo "<td>", htmlspecialchars($row['language']), "</td>\n";
					?>
					<?php
					echo "</tr>";
				}
			?>
		</table>
		
		
	</div>
	<?php 
   };
	?>
	<div id="formArea">
		<h2>Search Books</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input type="text" id="terms" name="terms">
			<select name='column'>
               <option value="title">Title</option>
               <option value="language">Language</option>
               <option value="author">Author</option>
            </select> 	
			<input type="submit" value="Search" name="submit">
		</form>
    </div>
	<div class="home">
 <a href='index.php'>Home Page</a>
</div>
    <?phpinclude("footer.php");?>
</body>
</html>