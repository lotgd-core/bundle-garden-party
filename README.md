# LoTGD Bunde Garden Party

Party in the Garden of all cities.

# Install
```bash
composer require lotgd-core/bundle-garden-party
```

# Default configuration
```yaml
# Note: party duration is 24 hours always.
lotgd_garden_party:
    # When does the part start. Valid format for DateTime object
    start: null # Required, Example: '2015-01-20 use this format YYYY-MM-DD'
    # How often is the party repeated? By default repeated every year
    repeat: P1Y # Example: 'http://php.net/manual/en/dateinterval.construct.php for examples of format'
    cake:
        # Cost per level for cake
        cost: 20
        # How many slices of cake can a player buy in one day?
        max: 3
    drink:
        # Cost per level for drink
        cost: 20
        # How many party drinks can a player buy in one day?
        max: 3
```
