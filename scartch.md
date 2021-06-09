# Original Statements

## Brand

```sql
SELECT
	brands.name,
	sum(gmv.turnover - (gmv.turnover * 0.21 / 100)) AS turnover
FROM
	brands
	JOIN gmv ON gmv.brand_id = brands.id
WHERE
	gmv.date >= DATE(NOW()) + INTERVAL - 7 DAY
GROUP BY
	brands.name;
```

## Daily

```sql
SELECT
	id,
	date,
	turnover - (turnover * 0.21 / 100)
FROM
	gmv
WHERE
	date >= DATE(NOW()) + INTERVAL - 7 DAY;
```

# Modified

## Daily

```sql
SELECT
	id,
	date,
	turnover - (turnover * 0.21 / 100)
FROM
	gmv
WHERE
	date BETWEEN to AND from
```
