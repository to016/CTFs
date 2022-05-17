package main

import (
	"fmt"
)

const (
	viperPort     = "PORT"
	viperLogLevel = "LOG_LEVEL"

	viperMySQLUsername = "MYSQL_USER"
	viperMySQLPassword = "MYSQL_PASS"
	viperMySQLHost     = "MYSQL_HOST"
	viperMySQLPort     = "MYSQL_PORT"
	viperMySQLDatabase = "MYSQL_DB"
)

type Config struct {
	Port     int `json:"port" mapstructure:"port"`
	LogLevel int `json:"log_level" mapstructure:"log_level"`

	Name string `json:"mysql_name" mapstructure:"mysql_name"`
}

func (c Config) SqlDSN() string {
	return fmt.Sprintf("%s:%s@tcp(%s:%s)/%s", "db_user", "db_password", "db", "3306", "myDB")
}
