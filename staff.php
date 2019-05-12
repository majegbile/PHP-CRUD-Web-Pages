<?php
 /*
    Mojeed Ajegbile
    CSCI 5060
    Assignment 5 

 */ 
    $title="Staff Book Tool";
    require('database.php');
	include("header.php");
	require('db_operations.php');
?>
<?php
	$operation = filter_input(INPUT_POST, 'operation');
	if ($operation == 'insert') {
		$title = filter_input(INPUT_POST, 'title');
		$pages = filter_input(INPUT_POST, 'pages', FILTER_VALIDATE_INT);
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
        $author = filter_input(INPUT_POST, 'author');
        $language = filter_input(INPUT_POST, 'language');
		
		$sql = "INSERT INTO books
			    (title, pages, year,author,language) 
		        VALUES 
				(:title, :pages, :year, :author, :language)";
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->bindValue(':pages', $pages, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':author', $author, PDO::PARAM_STR);
        $stmt->bindValue(':language', $language, PDO::PARAM_STR);
		
		if ($stmt->execute() == false) {
			echo "WARNING: error inserting new item<br>";
		}
		
	} else if ($operation == "delete") {
		$bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
		
		$sql = "delete from books where book_id = :bookId";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);
		
		if ($stmt->execute() == false) {
			echo "WARNING: error deleting item<br>";
		}
		
	} else if ($operation == "update_form") {
		$bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);		
	
		$sql = "select book_id, title, pages, year, author, language 
		        from books 
				where book_id = :bookId";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);
		
		if ($stmt->execute() == false) {
			echo "WARNING: error deleting item<br>";
		} else {
			
			if ($stmt->rowCount() === 1) {
				$record = $stmt->fetch();
				
				$bookId = $record['book_id'];
				$title = $record['title'];
				$pages = $record['pages'];
                $year = $record['year'];
                $author = $record['author'];
                $language = $record['language'];

			} else {
				# cancels the update
				$operation = "";
			}
			
			$stmt->closeCursor();
		}
		
	} else if ($operation == "update_database") {
		
		$bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
		$title= filter_input(INPUT_POST, 'title');
		$pages = filter_input(INPUT_POST, 'pages', FILTER_VALIDATE_INT);
        $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
        $author= filter_input(INPUT_POST, 'author');
        $language= filter_input(INPUT_POST, 'language');
		
		$sql = "update books 
		        set title = :title,
				    pages = :pages, 
                    year = :year,
                    author= :author,
                    language= :language 
				where book_id = :bookId";
		
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->bindValue(':pages', $pages, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':author', $author, PDO::PARAM_STR);
        $stmt->bindValue(':language', $language, PDO::PARAM_STR);
		$stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);
		
		if ($stmt->execute() == false) {
			echo "WARNING: error updating item<br>";
		}
		
	}

	$sortOrder = filter_input(INPUT_GET, 'sort_order');
	if (empty($sortOrder)) {
		$sortOrder = filter_input(INPUT_POST, 'sort_order');
	}
	
	$sql = "select book_id, title, pages, year, author, language from books ";
	
	if ($sortOrder === 'title') {
		$sql .= "order by title";
	} else if ($sortOrder === 'pages') {
		$sql .= "order by pages";
    } 
     else if ($sortOrder === 'author') {
		$sql .= "order by author";
    }
    else if ($sortOrder === 'year') {
		$sql .= "order by year";
    }
    else if ($sortOrder === 'language') {
    $sql .= "order by language";
    }
    else {
		$sql .= "order by title";
	}
	
	$stmt = $db->prepare($sql);
	$stmt->execute();
	
	$results = $stmt->fetchAll();
	$stmt->closeCursor();
	
	?>
	<div id="items">
		<h2>Current Inventory</h2>
		<table>
			<tr>
				<th><a href="?sort_order=title">title</a></th>
				<th><a href="?sort_order=pages">pages</a></th>
                <th><a href="?sort_order=year">year</a></th>
                <th><a href="?sort_order=author">author</a></th>
                <th><a href="?sort_order=language">language</a></th>
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
					<td>
						<form method="post" 
							  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
						      class="tableForm">
							<input type="hidden" name="sort_order" 
								   value="<?php echo htmlspecialchars($sortOrder); ?>" >
							<input type="hidden" name="operation" 
								   value="delete" >
							<input type="hidden" name="book_id" 
								   value="<?php echo $row['book_id']; ?>" >
							<input type="submit" 
								   value="Delete">
						</form>
					</td>
					<td>
						<form method="post"
							  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
						      class="tableForm">
							<input type="hidden" name="sort_order" 
								   value="<?php echo htmlspecialchars($sortOrder); ?>" >
							<input type="hidden" name="operation" 
								   value="update_form" >
							<input type="hidden" name="book_id" 
								   value="<?php echo $row['book_id']; ?>" >
							<input type="submit" 
								   value="Update">
						</form>
					</td>
					<?php
					echo "</tr>";
				}
			?>
		</table>
		
	</div>
	
	
	<div id="formArea">
		<h2>
			<?php if ($operation == "update_form") { ?>
				Update
			<?php } else { ?>
				Add
			<?php } ?>
			Books
		</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<?php if ($operation == "update_form") { ?>
				<input type="hidden" name="operation" value="update_database">
			<?php } else { ?>
				<input type="hidden" name="operation" value="insert">
			<?php } ?>
			
			<input type="hidden" name="sort_order" 
			       value="<?php echo htmlspecialchars($sortOrder); ?>">
			
			<?php if ($operation == "update_form") : ?>
				<input type="hidden" name="book_id" 
				       value="<?php echo $bookId; ?>">
			<?php endif ?>
			
			<label>title:</label>
			<input type="text" id="title" name="title" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($title); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<label>pages:</label>
			<input type="number" id="pages" name="pages" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($pages); ?>" 
					<?php endif ?>
					required="required">
			<br>
			<label>year:</label>
			<input type="number" id="year" name="year" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($year); ?>" 
					<?php endif ?>
					required="required">
            <br>
            <label>author:</label>
			<input type="text" id="author" name="author" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($author); ?>" 
					<?php endif ?>
					required="required">
            <br>
            <label>language:</label>
			<input type="text" id="language" name="language" 
					<?php if ($operation == "update_form") : ?>
						value="<?php echo htmlspecialchars($language); ?>" 
					<?php endif ?>
					required="required">
			<br>
			
			<?php if ($operation == "update_form") { ?>
				<input type="submit" value="Update item">
			<?php } else { ?>
				<input type="submit" value="Add item">
			<?php } ?>
			
			<input type="reset">
			
			<?php if ($operation == "update_form"): ?>
				<input type="button" value="Cancel update"
						onclick="location.href='?sort_order=<?php echo $sortOrder; ?>'">
			<?php endif ?>
			
		</form>
    </div>
	<div class="home">
 <a href='index.php'>Home Page</a>
</div>
    <?php include("footer.php");?>
</body>
</html>