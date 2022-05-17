package migrations

import (
	"database/sql"

	"hcmus.ctf/challenge/database"
	"hcmus.ctf/challenge/utils"
	"fmt"
	"strconv"
	"os"
)


const (
	credTable = "credential"
	dataTable = "data"
)

var secretData = os.Getenv("FLAG")

func Migrate(db *database.MySqlDB) error {
	err := DropTable(db.DB)
	if err != nil {
		return err
	}
	err = CreateCredTable(db.DB)
	if err != nil {
		return err
	}
	err = CreateDataTable(db.DB)
	if err != nil {
		return err
	}
	err = InitData(db.DB)
	if err != nil {
		return err
	}
	return nil
}

func DropTable(db *sql.DB) error {
	drop1 := "DROP TABLE IF EXISTS `data`"
	drop2 := "DROP TABLE IF EXISTS `credential`"

	stmt, err := db.Prepare(drop1)
	if err != nil {
		return err
	}

	defer stmt.Close()

	_, err = stmt.Exec()

	stmt, err = db.Prepare(drop2)
	if err != nil {
		return err
	}

	_, err = stmt.Exec()

	return err
}
func CreateCredTable(db *sql.DB) error {
	createCredQuery := "CREATE TABLE IF NOT EXISTS `credential` ( " +
		"`id` int NOT NULL AUTO_INCREMENT," +
		"`username` varchar(100) NOT NULL," +
		"`password` varchar(50) NOT NULL," +
		"PRIMARY KEY (`id`)," +
		"KEY `username` (`username`)" +
		")"

	stmt, err := db.Prepare(createCredQuery)
	if err != nil {
		return err
	}

	defer stmt.Close()

	_, err = stmt.Exec()

	return err
}

func CreateDataTable(db *sql.DB) error {	
	createDataTable := "CREATE TABLE IF NOT EXISTS `data` (" +
		"`username` varchar(100) NOT NULL," +
		"`key` varchar(100) NOT NULL," +
		"`data` varchar(100) NOT NULL," +
		"PRIMARY KEY (`username`, `key`)," +
		"KEY `username` (`username`)" +
		")"
	stmt, err := db.Prepare(createDataTable)
	if err != nil {
		return err
	}

	defer stmt.Close()

	_, err = stmt.Exec()

	return err
}

func InitData(db *sql.DB) error {
	for i := 1; i < 10; i++ {
		query := fmt.Sprintf("INSERT INTO `%s`(`username`, `password`) VALUES (?,?)", credTable)
		stmt, err := db.Prepare(query)
		if err != nil {
			return err
		}
		defer stmt.Close()
		username := "admin_" + strconv.Itoa(i)
		password := utils.GenerateRandomString(20)
		_, err = stmt.Exec(username, password)
		if err != nil {
			return err
		}

		query = fmt.Sprintf("INSERT INTO `%s`(`username`, `key`, `data`) VALUES (?,?,?)", dataTable)
		stmt, err = db.Prepare(query)
		if err != nil {
			return err
		}
		defer stmt.Close()

		_, err = stmt.Exec(username, "key", secretData)
		if err != nil {
			return err
		}
	}
	return nil
}