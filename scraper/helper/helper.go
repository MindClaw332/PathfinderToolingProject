package helper

import (
	"database/sql"
	"fmt"
	"strconv"
	"strings"
	"unicode"
)

// find the first number in the string and return only the number as an integer
func ExtractNumber(s string) (int, error) {
	trimmed := strings.TrimSpace(s)
	var numStr strings.Builder

	for _, rune := range trimmed {
		if unicode.IsDigit(rune) {
			numStr.WriteRune(rune)
		} else if numStr.Len() > 0 {
			break
		}
	}

	if numStr.Len() == 0 {
		return 0, fmt.Errorf("no number found in string: %q", s)
	}

	return strconv.Atoi(numStr.String())
}

// finds the index of a string inside of a given slice
func FindStringIndex(slice []string, target string) int {
	target = strings.ToLower(target)

	for index, element := range slice {
		if element == target {
			return index
		}
	}
	return -1
}

// gets the last id from a given table in a database
func GetLastIdFromTable(tableName string, databaseConn *sql.DB) (int, error) {
	var maxID int
	query := fmt.Sprintf("SELECT MAX(id) FROM %v", tableName)
	row, err := databaseConn.Query(query)
	if err == sql.ErrNoRows {
		return 0, nil
	} else if err != nil {
		return 0, err
	}
	row.Next()
	row.Scan(&maxID)
	return maxID, nil
}
