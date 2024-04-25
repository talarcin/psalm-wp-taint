#!/usr/bin/env php
<?php

namespace PsalmWpTaint;

shell_exec('./vendor/bin/psalm --taint-analysis');