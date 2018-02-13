<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/15/2014
 */
class TermFetcher
{
    const DB_TABLE = "term";
    const DB_COLUMN_ID = "id";
    const DB_COLUMN_NAME = "name";
    const DB_COLUMN_START_DATE = "start_date";
    const DB_COLUMN_END_DATE = "end_date";

    public static function whereIdIn(array $ids){
        foreach($ids as $key => $id){
            $termBindParams[] = ':id_' . $key;
        }
        $termBindParams = implode(', ', $termBindParams);

        $query = "SELECT  `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE
            . "`,		 `" . self::DB_COLUMN_END_DATE . "`
			FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
            WHERE `" . self::DB_COLUMN_ID . "` in ({$termBindParams})";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            foreach($ids as $key => $termId){
               $query->bindValue(":id_{$key}", $termId, PDO::PARAM_INT);
            }

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not retrieve data from database .: ");
        } // end catch
    }

//m-d-Y h:i A
    public static function retrieveAllButCur()
    {
        $query =
            "SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE . "`,
			 `" . self::DB_COLUMN_END_DATE . "`
			 FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			 WHERE (:now NOT BETWEEN `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" .
            TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`)
			 order by `" .
            self::DB_TABLE . "`.`" . self::DB_COLUMN_START_DATE . "` DESC";

        try {
            date_default_timezone_set('Europe/Athens');
            $now = new DateTime();
            $now = $now->format(Dates::DATE_FORMAT_IN);

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':now', $now, PDO::PARAM_STR);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not retrieve terms data from database.");
        }
    }

    //m-d-Y h:i A
    public static function retrieveAll()
    {
        $query =
            "SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE . "`,
			 `" . self::DB_COLUMN_END_DATE . "`
			 FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			order by `" .
            self::DB_TABLE . "`.`" . self::DB_COLUMN_START_DATE . "` DESC";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not retrieve terms data from database.");
        }
    }


    public static function retrieveCurrTerm()
    {
        $query =
            "SELECT `" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_NAME . "`, `" . self::DB_COLUMN_START_DATE . "`,
            	`" . self::DB_COLUMN_END_DATE . "`
			 	FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			 	WHERE :now BETWEEN `" . self::DB_COLUMN_START_DATE . "` AND `" . self::DB_COLUMN_END_DATE . "`";

        try {
            date_default_timezone_set('Europe/Athens');
            $now = new DateTime();
            $now = $now->format(Dates::DATE_FORMAT_IN);

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':now', $now, PDO::PARAM_STR);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not retrieve current term from database.");
        }
    }


    public static function retrieveSingle($id)
    {
        $query = "SELECT  `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE
            . "`,		 `" . self::DB_COLUMN_END_DATE . "`
			FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not retrieve data from database .: ");
        } // end catch
    }

    public static function updateName($id, $newName)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_NAME . "`= :newName
					WHERE `id`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newName', $newName, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update term name");
        }
    }

    public static function  updateStartingDate($id, $newStartingDate)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_START_DATE . "`= :newName
					WHERE `id`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newName', $newStartingDate, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update starting date");
        }
    }


    public static function updateSingleColumn($id, $column, $value, $valueType)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . $column . "`= :column
					WHERE `id`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':column', $value, $valueType);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something went wrong. Could not update term table.");
        }
    }

    public static function insert($name, $startDate, $endDate)
    {
        date_default_timezone_set('Europe/Athens');

        $startDate = $startDate->format(Dates::DATE_FORMAT_IN);
        $endDate = $endDate->format(Dates::DATE_FORMAT_IN);

        try {
            $query = "INSERT INTO `" . App::getDbName() . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_NAME .
                "`, `" . self::DB_COLUMN_START_DATE . "`, `" . self::DB_COLUMN_END_DATE . "`)
				VALUES(
					:name,
					:start_date,
					:end_date
				)";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':start_date', $startDate);
            $query->bindParam(':end_date', $endDate);
            $query->execute();
            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not insert term data into database.");
        }
    }

    public static function idExists($id)
    {
        try {
            $query = "SELECT COUNT(" . self::DB_COLUMN_ID . ")
			FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "` = :id";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === 0) return false;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("There a problem with the database.<br/> Aborting process.");
        }

        return true;
    }


    public static function delete($id)
    {
        try {
            $query = "DELETE FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not delete term from database.");
        }
    }


    public static function existsName($name)
    {
        try {
            $query = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . App::getDbName() . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :name";
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not check if term name already exists on database. <br/> Aborting process.");
        }

        return true;

    }
}
