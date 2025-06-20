package tests

import (
	"fmt"
	"log"
	"os"

	// this will automatically load your .env file:
	"github.com/joho/godotenv"
	_ "github.com/joho/godotenv/autoload"
)

// test if we have an env
func TestEnv() {
	err := godotenv.Load("../.env")
	if err != nil {
		log.Fatal(err)
	}
	test := os.Getenv("DB_CONNECTION")
	fmt.Println(test)
}
