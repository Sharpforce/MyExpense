#!/bin/bash

process_count=$(pgrep /usr/bin/python3 | wc -l)

if [ "$process_count" -eq 4 ]; then
  exit 0
else
  exit 1
fi