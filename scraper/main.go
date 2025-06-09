package main

import (
	"database/sql"
	"fmt"
	"log"
	"strconv"
	"time"

	"github.com/MindClaw332/PathfinderToolingProject/config"
	"github.com/MindClaw332/PathfinderToolingProject/helper"
	"github.com/go-rod/rod"
	_ "github.com/go-sql-driver/mysql"
)

type Creature struct {
	name       string
	size       string
	level      int
	hp         int
	ac         int
	fortitude  int
	reflex     int
	will       int
	perception int
	senses     string
	speed      string
	rarity     string
	traits     []string
}

func browseToPage(browser *rod.Browser, link string) *rod.Page {
	// Create a new page
	page := browser.MustPage(link).MustWaitStable()
	fmt.Println("page visited")
	return page
}

func parseTableData(index int, creature *Creature, data *rod.Element) error {
	switch index {
	case 0:
		creature.name = data.MustElement("a").MustText()
	case 1, 2:
		return nil
	case 3:
		creature.rarity = data.MustText()
	case 4:
		creature.size = data.MustText()
	case 5:
		traitSlice := []string{}
		traits, err := data.Elements("a")
		if err != nil {
			return err
		}
		for _, trait := range traits {
			traitSlice = append(traitSlice, trait.MustText())
		}
		creature.traits = traitSlice
	case 6:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.level = string
	case 7:
		string, err := helper.ExtractNumber(data.MustText())
		if err != nil {
			return err
		}
		creature.hp = string
	case 8:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.ac = string
	case 9:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.fortitude = string

	case 10:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.reflex = string
	case 11:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.will = string

	case 12:
		string, err := strconv.Atoi(data.MustText())
		if err != nil {
			return err
		}
		creature.perception = string

	case 13:
		creature.senses = data.MustText()
	case 14:
		creature.speed = data.MustText()
	default:
		return fmt.Errorf("unexpected column index: %d", index)
	}
	return nil
}

func main() {
	cfg, _ := config.GetConfig()
	dbString := fmt.Sprintf("%v:%v@tcp(%v:%v)/%v", cfg.Username, cfg.Password, cfg.Host, cfg.Port, cfg.Database)
	fmt.Printf("query: %v \n", dbString)
	db, err := sql.Open("mysql", dbString)
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	err = db.Ping()
	if err != nil {
		log.Fatal(err)
	}
	result, err := db.Exec(`INSERT INTO sizes (name) VALUES (?)`, "test")
	if err != nil {
		log.Fatal(err)
	}
	id, _ := result.LastInsertId()
	fmt.Println(id)
	fmt.Println(cfg)
	// Launch a new browser with default options, and connect to it.
	browser := rod.New().MustConnect()
	defer browser.MustClose()
	page := browseToPage(browser, "https://2e.aonprd.com/Creatures.aspx")
	// there will be a consent button that might block is which we need to click
	consentButton := page.MustElement("button.fc-cta-consent")
	fmt.Println("button found")
	//wait until it is actually enabled and click it
	consentButton.MustWaitEnabled().MustClick()
	fmt.Println("button clicked")
	// find the nethys search element because this has the shadow we need
	div := page.MustElement("nethys-search")

	// get the shadow root here we do want an error
	shadow, err := div.ShadowRoot()
	if err != nil {
		log.Fatal(err)
	}
	button, err := shadow.ElementR("button", "Load remaining")
	if err != nil {
		log.Fatal(err)
	}
	fmt.Println(button.MustText())
	button.MustClick().MustWaitStable()
	fmt.Println("button clicked")
	fmt.Println("waiting")
	time.Sleep(10 * time.Second)
	// get all of the rows from the shadow
	rows, err := shadow.Elements("tr")
	if err != nil {
		log.Fatal(err)
	}
	var creatures []Creature
	// looop the rows
	for index, row := range rows {
		fmt.Printf("%d: adding...", index)

		if index == 0 {
			continue
		}
		newCreature := Creature{}
		data, err := row.Elements("td")
		if err != nil {
			log.Fatal(err)
		}

		for index, tableData := range data {
			err := parseTableData(index, &newCreature, tableData)
			if err != nil {
				log.Fatal(err)
			}
		}
		creatures = append(creatures, newCreature)

	}

	// creatures = slices.Delete(creatures, 0, 1)
	fmt.Printf("%v", creatures)
	fmt.Println("done")
}
