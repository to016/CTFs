package main

import (
	"fmt"
	"os"
	"strings"

	"hcmus.ctf/challenge/database"
	"hcmus.ctf/challenge/migrations"
	"hcmus.ctf/challenge/pkg/api/data"
	"hcmus.ctf/challenge/pkg/auth"
	customizeMiddleware "hcmus.ctf/challenge/pkg/middleware"
	"hcmus.ctf/challenge/utils"
	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
	"github.com/ory/viper"

	log "github.com/sirupsen/logrus"
)

var config Config
var authHandler = new(auth.Handler)
var dataHandler = new(data.Handler)

func main() {
	// Echo instance
	e := echo.New()
	log.Info("Challenge starting...")
	log.WithFields(log.Fields{
		"config": config,
	}).Info("Loaded config successfully")

	if err := setup(); err != nil {
		log.WithFields(log.Fields{
			"error": err,
		}).Fatal("cannot connect to mysql instance")
		os.Exit(1)
	}
	// Middleware
	e.Use(middleware.Logger())
	e.Use(middleware.Recover())

	// Routes
	authGroup := e.Group("/auth")
	authGroup.POST("/register", authHandler.Register)
	authGroup.POST("/login", authHandler.Login)
	apiGroup := e.Group("/api")
	apiGroup.Use(customizeMiddleware.Auth)
	apiGroup.POST("/insert-data", dataHandler.InsertData)
	apiGroup.POST("/data", dataHandler.GetData)

	// Start server
	e.Logger.Fatal(e.Start(fmt.Sprintf(":%d", config.Port)))
}

func init() {
	viper.SetEnvKeyReplacer(strings.NewReplacer(".", "_"))
	viper.AutomaticEnv()

	config.LogLevel = utils.EnvAtoI(os.Getenv(viperLogLevel), 4)
	config.Port = utils.EnvAtoI(os.Getenv(viperPort), 8080)
	config.Name = strings.TrimSuffix(os.Getenv(viperMySQLDatabase), "\n")

	log.SetLevel(log.Level(config.LogLevel))
	log.SetReportCaller(true)
}

func setup() error {
	// init and connect database
	dsn := config.SqlDSN()
	db := &database.MySqlDB{
		DSN: dsn,
	}
	if err := db.Connect(); err != nil {
		log.WithFields(log.Fields{
			"error": err,
		}).Fatal("cannot connect to mysql instance")
		return err
	}

	authHandler.DB = db
	dataHandler.DB = db

	err := migrations.Migrate(db)
	if err != nil {
		log.WithFields(log.Fields{
			"error": err,
		}).Fatal("cannot migrate")
		return err
	}

	return nil
}
