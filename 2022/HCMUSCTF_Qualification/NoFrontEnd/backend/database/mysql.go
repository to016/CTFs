package database

import (
	"database/sql"
	"fmt"
	"time"

	"hcmus.ctf/challenge/model"
)

const (
	credTable = "credential"
	dataTable = "data"
)

type MySqlDB struct {
	DB  *sql.DB
	DSN string
}

func (d *MySqlDB) Connect() error {
	db, err := sql.Open("mysql", d.DSN)
	if err != nil {
		return err
	}

	db.SetConnMaxLifetime(time.Minute * 1)
	db.SetMaxOpenConns(2)
	db.SetMaxIdleConns(1)
	d.DB = db
	return nil
}

func (db *MySqlDB) InsertCredentials(cred model.Credentials) (bool, error) {
	query := fmt.Sprintf("INSERT INTO `%s`(`username`, `password`) VALUES (?,?)", credTable)
	stmt, err := db.DB.Prepare(query)
	if err != nil {
		return false, err
	}
	defer stmt.Close()

	_, err = stmt.Exec(cred.Username, cred.Password)
	if err != nil {
		return false, err
	}
	return true, nil
}
func (db *MySqlDB) CheckCredentials(cred model.Credentials) (bool, error) {
	query := fmt.Sprintf("SELECT `username` FROM `%s` WHERE `username` = ? AND `password` = ?", credTable)
	stmt, err := db.DB.Prepare(query)
	if err != nil {
		return false, err
	}
	defer stmt.Close()

	rows, err := stmt.Query(cred.Username, cred.Password)
	if err != nil {
		return false, err
	}
	
	var username string
	for rows.Next() {
		err := rows.Scan(&username)
		if err != nil {
			continue
		}
	}
	return len(username) > 0, err
}
func (db *MySqlDB) InsertData(username string, userData model.UserPostData) (bool, error) {
	query := fmt.Sprintf("INSERT INTO `%s` (`username`, `key`, `data`) VALUES (?,?,?)", dataTable)
	stmt, err := db.DB.Prepare(query)
	if err != nil {
		return false, err
	}
	defer stmt.Close()

	_, err = stmt.Exec(username, userData.Key, userData.Data)
	if err != nil {
		return false, err
	}

	return true, err
}

func (db *MySqlDB) GetData(username string, userData model.UserPostData) (string, error) {
	query := fmt.Sprintf("SELECT `data` FROM `%s` WHERE `username` = ? AND `key` = ?", dataTable)
	stmt, err := db.DB.Prepare(query)
	if err != nil {
		return "", err
	}
	defer stmt.Close()

	rows, err := stmt.Query(username, userData.Key)
	if err != nil {
		return "", err
	}

	var respdata string
	for rows.Next() {
		err := rows.Scan(&respdata)
		if err != nil {
			continue
		}
	}
	return respdata, err
}
