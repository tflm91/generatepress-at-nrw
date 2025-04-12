<?php

/* universal function for database query */
function query_database($query, $params = [], $single_result = false) {
    global $wpdb;

    if (!empty($params)) {
        $query = $wpdb->prepare($query, $params);
    }


    return $single_result ? $wpdb->get_row($query) : $wpdb->get_results($query);
}

/* retrieve all entries of a table */
function get_all($table, $conditions = [], $order_by = null) {
    $where_clauses = [];
    $params = [];

    foreach ($conditions as $column => $value) {
        $where_clauses[] = "$column = %s";
        $params[] = $value;
    }

    $query = "SELECT * FROM $table";

    if (!empty($where_clauses)) {
        $query .= ' WHERE ' . implode(' AND ', $where_clauses);
    }

    if ($order_by) {
        $query .= " ORDER BY $order_by";
    }

    return query_database($query, $params);
}

/* retrieve single entry by ID */
function get_by_id($table, $id) {
    $query = "SELECT * FROM $table WHERE id=%d";
    return query_database($query, [$id], true);
}

/* retrieve all entries of a specified category */
function get_by_category($table, $category_id, $order_by = null) {
    $query = "SELECT * FROM $table WHERE categoryId = %d";
    if ($order_by) {
        $query .= " ORDER BY $order_by";
    }
    return query_database($query, [$category_id]);
}

/* retrieve all objects of an m:n-connection */
function get_connected(
    $connection_table,
    $search_column,
    $target_table,
    $connection_column,
    $search_id,
    $conditions = [],
    $order_by = null
) {
    $where_clauses = [];
    $params = [$search_id];

    foreach ($conditions as $column => $value) {
        $where_clauses[] = "$column = %s";
        $params[] = $value;
    }

    $query = "SELECT {$target_table}.* FROM {$target_table}"
              . " JOIN {$connection_table} ON {$target_table}.id = {$connection_table}.{$connection_column}"
              . " WHERE {$connection_table}.{$search_column} = %d";

    if (!empty($where_clauses)) {
        $query .= ' AND ' . implode(' AND ', $where_clauses);
    }

    if ($order_by) {
        $query .= " ORDER BY {$order_by}";
    }

    return query_database($query, $params);
}

/* retrieve all IDs of an m:n-connection */
function get_connected_ids($connection_table, $search_column, $connection_column, $search_id) {
    $query = "SELECT {$connection_column} FROM {$connection_table} WHERE {$search_column} = %d";
    $results = query_database($query, [$search_id]);
    return array_map(fn($item) => $item->{$connection_column}, $results);
}


/* check if a category has objects */
function category_has_objects($table, $category_id): bool {
    $query = "SELECT COUNT(*) AS count FROM $table WHERE categoryId = %d";
    $result = query_database($query, [$category_id], true);
    return $result->count > 0;
}

/* check if an item has connected objects */
    function has_connected_objects(
    $connection_table,
    $search_column,
    $target_table,
    $connection_column,
    $search_id,
    $conditions = [],
): bool {
        $where_clauses = [];
        $params = [$search_id];

        foreach ($conditions as $column => $value) {
            $where_clauses[] = "$column = %s";
            $params[] = $value;
        }

        $query = "SELECT COUNT(*) AS count FROM {$connection_table}"
            . " JOIN {$target_table} ON {$connection_table}.{$connection_column} = {$target_table}.id"
            . " WHERE {$connection_table}.{$search_column} = %d";

        if (!empty($where_clauses)) {
            $query .= ' AND ' . implode(' AND ', $where_clauses);
        }

        $result =  query_database($query, $params, true);
        return $result->count > 0;
}

/* list all objects without m:n-connection */
function get_unconnected_objects(
    $main_table,
    $connection_table,
    $main_id_column,
    $conditions = [],
    $order_by = null) {

    $where_clauses = [];
    $params = [];

    foreach ($conditions as $column => $value) {
        $where_clauses[] = "$column = %s";
        $params[] = $value;
    }

    $query = "SELECT * FROM $main_table WHERE id NOT IN"
        . " (SELECT DISTINCT {$main_id_column} FROM $connection_table)";

    if (!empty($where_clauses)) {
        $query .= ' AND ' . implode(' AND ', $where_clauses);
    }

    if ($order_by) {
        $query .= " ORDER BY {$order_by}";
    }

    return query_database($query, $params);
}

/* list all objects which are not m:n-connected to a specified object */
function get_unconnected_to_object($connection_table, $search_column, $target_table, $connection_column, $search_id, $order_by = null) {
    $query = "SELECT * FROM $target_table WHERE id NOT IN (
    SELECT {$connection_column} FROM {$connection_table} WHERE {$search_column} = %d
    )";

    if ($order_by) {
        $query .= " ORDER BY {$order_by}";
    }

    return query_database($query, [$search_id]);
}