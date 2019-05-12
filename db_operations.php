<?php
function db_operations($operation){
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
		
		$bookId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
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
		$stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
		
		if ($stmt->execute() == false) {
			echo "WARNING: error updating item<br>";
		}
		
	}

}
?>